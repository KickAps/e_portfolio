<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Project;
use App\Form\ProjectType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends AbstractController
{
    private $em;

    private function addImagesToProject(array $images, Project $project)
    {
        foreach ($images as $image)
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
    }

    public function __construct(EntityManagerInterface $em)
    {
        // Initiate the entity manager
        $this->em = $em;
    }

    /**
     * @return Twig template that prints all projects
     */
    public function index()
    {
        // Template render
        return $this->render('project/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    /**
     * @param  Project
     * @return Twig template that prints the project given
     */
    public function show(Project $project)
    {
        if (!$project->isOwnedBy($this->getUser()))
        {
            throw $this->createNotFoundException();
        }
        // Template render
        return $this->render('project/show.html.twig', compact('project'));
    }

    /**
     * @param  Request
     * @param  EntityManagerInterface
     * @return Twig template
     */
    public function create(Request $request)
    {
        $project = new Project;

        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            if (is_null($project->getMainImage()))
            {
                $project->setMainImage($this->getParameter('default_image'));
            }

            $images = $form->get('images')->getData();

            $this->addImagesToProject($images, $project);

            // Associate the project to the current user
            $this->getUser()->addProject($project);

            $this->em->persist($project);
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Projet créé avec succés !');

            // Redirection to the show page
            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId()
            ]);
        }

        // Template render
        return $this->render('project/create.html.twig', [
            'myForm' => $form->createView()
        ]);
    }

    /**
     * @param  Project
     * @param  Request
     * @return Twig template
     */
    public function update(Project $project, Request $request)
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
                $project->setMainImage($this->getParameter('default_image'));
            }

            $images = $form->get('images')->getData();

            $this->addImagesToProject($images, $project);

            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Projet modifié avec succés !');

            // Redirection
            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId()
            ]);
        }

        // Template render
        return $this->render('project/update.html.twig', [
            'myForm' => $form->createView(),
            'project' => $project
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
            $this->addFlash('info', 'Projet supprimé avec succés !');
        }

        // Redirection
        return $this->redirectToRoute('app_project');
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
                $project->setMainImage($this->getParameter('default_image'));
            }

            $project->removeImage($image);

            $this->em->remove($image);
            $this->em->flush();

            // Flash message
            $this->addFlash('info', 'Image supprimée avec succés !');
        }

        // Redirection
        return $this->redirectToRoute('app_project_update', [
            'id' => $project->getId()
        ]);
    }
}
