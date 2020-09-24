<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProjectController extends AbstractController
{
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
}
