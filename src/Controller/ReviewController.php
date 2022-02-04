<?php

namespace App\Controller;

use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ReviewController extends AbstractController {

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        // Initiate the entity manager
        $this->em = $em;
    }

    public function index(): Response {
        return $this->render('review/index.html.twig', [
            'controller_name' => 'ReviewController',
        ]);
    }

    public function create(Request $request, UserController $userController): RedirectResponse|Response {
        $review = new Review();

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Associate the project to the current user
            $this->getUser()->addProject($project);

            $this->em->persist($review);
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Votre avis a bien été enregistré, merci !');

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
}
