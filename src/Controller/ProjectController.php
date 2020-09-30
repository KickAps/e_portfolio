<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProfilRepository;
use App\Repository\ProjectRepository;
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
        $this->em = $em;
    }

    /**
     * @param  ProjectRepository 
     * @return Twig template that prints all projects
     */
    public function index(ProfilRepository $profilRepository)
    {
        // TODO : Get the current user
        $profil = $profilRepository->findAll()[0];
        $projects = $profil->getProjects();

        // Template render
        return $this->render('project/index.html.twig', compact('projects'));
    }

    /**
     * @param  Project
     * @return Twig template that prints the project given
     */
    public function show(Project $project)
    {
        // Template render
        return $this->render('project/show.html.twig', compact('project'));
    }

    /**
     * @param  Request
     * @param  EntityManagerInterface
     * @return Twig template
     */
    public function create(Request $request, ProfilRepository $profilRepository)
    {
        $project = new Project;

        // TODO : Get the current user
        $profil = $profilRepository->findAll()[0];

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $images = $form->get('images')->getData();

            $this->addImagesToProject($images, $project);

            // Associate the project to the current profil
            $profil->addProject($project);

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
        $form = $this->createForm(ProjectType::class, $project, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
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
    public function delete(Project $project, Request $request, ProfilRepository $profilRepository)
    {
        $csrf_token = $request->request->get('csrf_token');
        if ($this->isCsrfTokenValid('project_deletion_' . $project->getId(), $csrf_token))
        {
            foreach ($project->getImages() as $image)
            {
                unlink($this->getParameter('images_dir') . $image->getName());
            }

            // TODO : Get the current user
            $profil = $profilRepository->findAll()[0];
            $profil->removeProject($project);

            $this->em->remove($project);
            $this->em->flush();

            // Flash message
            $this->addFlash('info', 'Projet supprimé avec succés !');
        }

        // Redirection
        return $this->redirectToRoute('app_projects');
    }

    public function deleteImage(Image $image, Request $request)
    {
        unlink($this->getParameter('images_dir') . $image->getName());

        $project = $image->getProject();
        $project->removeImage($image);
        $this->em->remove($image);
        $this->em->flush();

        // Redirection
        return $this->redirectToRoute('app_project_update', [
            'id' => $project->getId()
        ]);
    }
}
