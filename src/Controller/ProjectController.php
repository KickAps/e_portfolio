<?php

namespace App\Controller;

use App\Controller\UserController;
use App\Entity\Image;
use App\Entity\Project;
use App\Entity\User;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends AbstractController
{
    private $em;

    private function addImagesToProject(array $images, Project $project)
    {
        foreach ($images as $image)
        {
            if (!$this->getUser()->isImagesLimitReached($project))
            {
                $i = new Image;
                $filename = $image->getClientOriginalName();
                $uniqueFilename = md5(uniqid()). "-" . $filename;

                if ($filename === $project->getMainImage())
                {
                    $project->setMainImage($uniqueFilename);
                }

                $i->setName($filename);
                $i->setUniqueName($uniqueFilename);

                // Move to uplaods folder
                $image->move($this->getParameter('images_dir'), $uniqueFilename);

                // Associate the image to the project
                $project->addImage($i);
            }
            else
            {
                // Flash message
                $this->addFlash(
                    'info',
                    'Cependant certaines images n\'ont pas pu être ajoutées (maximum ' . $this->getUser()->getImagesLimit() . ').'
                );
                break;
            }
        }
    }

    public function __construct(EntityManagerInterface $em)
    {
        // Initiate the entity manager
        $this->em = $em;
    }

    /**
     * @return Twig template that prints all projects
     */
    public function index(User $offlineUser, ProjectRepository $projectRepository, UserController $userController)
    {
        list($user, $spectator) = $userController->isSpectator($offlineUser);

        $projects = $spectator ? $projectRepository->findBy(["isVisible" => true]) : $user->getProjects();

        $userController->isVerified();

        // Template render
        return $this->render('project/index.html.twig', [
            'projects' => $projects,
            'user' => $user,
            'spectator' => $spectator
        ]);
    }

    /**
     * @param  Project
     * @return Twig template that prints the project given
     *
     * @ParamConverter("offlineUser", options={"mapping": {"externalId": "externalId"}})
     */
    public function show(User $offlineUser, Project $project, UserController $userController)
    {
        list($user, $spectator) = $userController->isSpectator($offlineUser);

        if (!$project->isOwnedBy($user) or $spectator and !$project->isVisible())
        {
            throw $this->createNotFoundException();
        }

        $userController->isVerified();

        // Template render
        return $this->render('project/show.html.twig', [
            'project' => $project,
            'user' => $user,
            'spectator' => $spectator
        ]);
    }

    /**
     * @param  Request
     * @param  EntityManagerInterface
     * @return Twig template
     */
    public function create(Request $request, UserController $userController)
    {
        $project = new Project;

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if (is_null($project->getMainImage()))
            {
                $project->setMainImage($this->getParameter('default_jpg'));
            }

            $images = $form->get('images')->getData();

            $this->addImagesToProject($images, $project);

            // Associate the project to the current user
            $this->getUser()->addProject($project);

            $this->em->persist($project);
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Projet créé avec succès !');

            // Redirection to the show page
            return $this->redirectToRoute('app_project_show', [
                'externalId' => $this->getUser()->getExternalId(),
                'id' => $project->getId()
            ]);
        }

        $userController->isVerified();

        // Template render
        return $this->render('project/create.html.twig', [
            'myForm' => $form->createView(),
            'user' => $this->getUser(),
            'spectator' => false
        ]);
    }

    /**
     * @param  Project
     * @param  Request
     * @return Twig template
     */
    public function update(Project $project, Request $request, UserController $userController)
    {
        if (!$project->isOwnedBy($this->getUser()))
        {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ProjectType::class, $project, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if (is_null($project->getMainImage()))
            {
                $project->setMainImage($this->getParameter('default_jpg'));
            }

            $images = $form->get('images')->getData();

            $this->addImagesToProject($images, $project);

            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Projet modifié avec succès !');

            // Redirection
            return $this->redirectToRoute('app_project_show', [
                'externalId' => $this->getUser()->getExternalId(),
                'id' => $project->getId()
            ]);
        }

        $userController->isVerified();

        // Template render
        return $this->render('project/update.html.twig', [
            'myForm' => $form->createView(),
            'project' => $project,
            'user' => $this->getUser(),
            'spectator' => false
        ]);
    }

    public function hide(Project $project)
    {
        $project->setVisible(!$project->isVisible());

        $this->em->flush();

        // Redirection
        return $this->redirectToRoute('app_project_show', [
            'externalId' => $this->getUser()->getExternalId(),
            'id' => $project->getId()
        ]);
    }

    /**
     * @param  Project
     * @param  Request
     * @return Twig template
     */
    public function delete(Project $project, Request $request)
    {
        if (!$project->isOwnedBy($this->getUser()))
        {
            throw $this->createNotFoundException();
        }

        $csrf_token = $request->request->get('csrf_token');
        if ($this->isCsrfTokenValid('project_deletion_' . $project->getId(), $csrf_token))
        {
            foreach ($project->getImages() as $image)
            {
                unlink($this->getParameter('images_dir') . $image->getUniqueName());
            }

            $this->getUser()->removeProject($project);

            $this->em->remove($project);
            $this->em->flush();

            // Flash message
            $this->addFlash('info', 'Projet supprimé avec succès !');
        }

        // Redirection
        return $this->redirectToRoute('app_project', [
            'externalId' => $this->getUser()->getExternalId()
        ]);
    }

    public function deleteImage(Image $image, Request $request)
    {
        $csrf_token = $request->request->get('csrf_token');
        if ($this->isCsrfTokenValid('image_deletion_' . $image->getId(), $csrf_token))
        {
            unlink($this->getParameter('images_dir') . $image->getUniqueName());

            $project = $image->getProject();
            // If the deleted image is the main image
            if ($project->getMainImage() === $image->getUniqueName())
            {
                $project->setMainImage($this->getParameter('default_jpg'));
            }

            $project->removeImage($image);

            $this->em->remove($image);
            $this->em->flush();

            // Flash message
            $this->addFlash('info', 'Image supprimée avec succès !');
        }

        // Redirection
        return $this->redirectToRoute('app_project_update', [
            'externalId' => $this->getUser()->getExternalId(),
            'id' => $project->getId()
        ]);
    }
}
