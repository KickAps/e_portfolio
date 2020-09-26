<?php

namespace App\Controller;

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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param  ProjectRepository 
     * @return Twig template that prints all projects
     */
    public function index(ProjectRepository $projectRepository)
    {
        $projects = $projectRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('project/index.html.twig', compact('projects'));
    }

    /**
     * @param  Project
     * @return Twig template that prints the project given
     */
    public function show(Project $project)
    {
        return $this->render('project/show.html.twig', compact('project'));
    }

    /**
     * @param  Request
     * @param  EntityManagerInterface
     * @return Twig template
     */
    public function create(Request $request, ProfilRepository $repo)
    {
        $project = new Project;
        $profil = $repo->findAll()[0];

        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $project->setProfil($profil);
            $this->em->persist($project);
            $this->em->flush();

            $this->addFlash('success', 'Projet créé avec succés !');

            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId()
            ]);
        }

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
            $this->em->flush();

            $this->addFlash('success', 'Projet modifié avec succés !');

            return $this->redirectToRoute('app_project_show', [
                'id' => $project->getId()
            ]);
        }

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
        $csrf_token = $request->request->get('csrf_token');
        if ($this->isCsrfTokenValid('project_deletion_' . $project->getId(), $csrf_token))
        {
            $this->em->remove($project);
            $this->em->flush();

            $this->addFlash('info', 'Projet supprimé avec succés !');
        }
        return $this->redirectToRoute('app_projects');
    }
}
