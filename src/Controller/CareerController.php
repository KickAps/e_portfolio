<?php

namespace App\Controller;

use App\Entity\Career;
use App\Form\CareerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CareerController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        // Initiate the entity manager
        $this->em = $em;
    }

    public function index()
    {
        $range_data = [];
        $moment_data = [];
        $project_data = [];

        foreach ($this->getUser()->getCareer() as $career)
        {
            if ($career->getEndDate())
            {
                array_push($range_data, [
                    $career->getTitle(),
                    $career->getStartDate()->getTimestamp() * 1000,
                    $career->getEndDate()->getTimestamp() * 1000
                ]);
            }
            else
            {
                array_push($moment_data, [
                    "x" => $career->getStartDate()->getTimestamp() * 1000,
                    "y" => $career->getTitle()
                ]);
            }
        }

        foreach ($this->getUser()->getProjects() as $project)
        {
            array_push($project_data, [
                "x" => $project->getCreatedAt()->getTimestamp() * 1000,
                "y" => $project->getTitle(),
                "id" => $project->getId()
            ]);
        }

        $json_range_data = json_encode($range_data);
        $json_moment_data = json_encode($moment_data);
        $json_project_data = json_encode($project_data);

        // Template render
        return $this->render('career/index.html.twig', [
            'user' => $this->getUser(),
            'json_range_data' => $json_range_data, 
            'json_moment_data' => $json_moment_data,
            'json_project_data' => $json_project_data
        ]);
    }

    public function create(Request $request)
    {
        $career = new Career;

        $form = $this->createForm(CareerType::class, $career);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Associate the project to the current user
            $this->getUser()->addCareer($career);

            $this->em->persist($career);
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Parcours créé avec succés !');

            // Redirection to the show page
            return $this->redirectToRoute('app_career');
        }

        // Template render
        return $this->render('career/create.html.twig', [
            'myForm' => $form->createView()
        ]);
    }

    public function update(Career $career, Request $request)
    {
        if (!$career->isOwnedBy($this->getUser()))
        {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CareerType::class, $career, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Parcours modifié avec succés !');

            // Redirection
            return $this->redirectToRoute('app_career', [
                'id' => $career->getId()
            ]);
        }

        // Template render
        return $this->render('career/update.html.twig', [
            'myForm' => $form->createView(),
            'career' => $career
        ]);
    }

    public function delete(Career $career, Request $request)
    {
        if (!$career->isOwnedBy($this->getUser()))
        {
            throw $this->createNotFoundException();
        }

        $csrf_token = $request->request->get('csrf_token');
        if ($this->isCsrfTokenValid('career_deletion_' . $career->getId(), $csrf_token))
        {
            $this->getUser()->removeCareer($career);

            $this->em->remove($career);
            $this->em->flush();

            // Flash message
            $this->addFlash('info', 'Parcours supprimé avec succés !');
        }

        // Redirection
        return $this->redirectToRoute('app_career');
    }
}
