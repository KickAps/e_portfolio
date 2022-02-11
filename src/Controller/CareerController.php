<?php

namespace App\Controller;

use App\Controller\UserController;
use App\Entity\Career;
use App\Entity\Review;
use App\Entity\User;
use App\Form\CareerType;
use App\Form\ReviewType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CareerController extends AbstractController {
    private $em;

    public function __construct(EntityManagerInterface $em) {
        // Initiate the entity manager
        $this->em = $em;
    }

    public function index(User $offlineUser, UserController $userController) {
        $range_data = [];
        $moment_data = [];
        $project_data = [];

        list($user, $spectator) = $userController->isSpectator($offlineUser);

        foreach($user->getCareer() as $career) {
            if($career->getEndDate()) {
                $range_data[] = [
                    "x" => $career->getTitle(),
                    "start" => $career->getStartDate()->getTimestamp() * 1000,
                    "end" => $career->getEndDate()->getTimestamp() * 1000,
                    "id" => $career->getId()
                ];
            } else {
                $moment_data[] = [
                    "x" => $career->getStartDate()->getTimestamp() * 1000,
                    "y" => $career->getTitle(),
                    "id" => $career->getId()
                ];
            }
        }

        foreach($user->getProjects() as $project) {
            if(!$spectator or $project->isVisible()) {
                $project_data[] = [
                    "x" => $project->getCreatedAt()->getTimestamp() * 1000,
                    "y" => $project->getTitle(),
                    "id" => $project->getId(),
                    "tag" => $project->isVisible() ? "" : "masqué"
                ];
            }
        }

        $json_range_data = json_encode($range_data);
        $json_moment_data = json_encode($moment_data);
        $json_project_data = json_encode($project_data);

        $userController->isVerified();

        // Template render
        return $this->render('career/index.html.twig', [
            'user' => $user,
            'json_range_data' => $json_range_data,
            'json_moment_data' => $json_moment_data,
            'json_project_data' => $json_project_data,
            'spectator' => $spectator
        ]);
    }

    public function create(Request $request, UserController $userController) {
        $career = new Career;

        $form = $this->createForm(CareerType::class, $career);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Associate the project to the current user
            $this->getUser()->addCareer($career);

            $this->em->persist($career);
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Parcours créé avec succès !');

            // Redirection to the show page
            return $this->redirectToRoute('app_career', [
                'externalId' => $this->getUser()->getExternalId()
            ]);
        }

        $userController->isVerified();

        // Template render
        return $this->render('career/create.html.twig', [
            'myForm' => $form->createView(),
            'user' => $this->getUser(),
            'spectator' => false
        ]);
    }

    public function update(Career $career, Request $request, UserController $userController) {
        if(!$career->isOwnedBy($this->getUser())) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(CareerType::class, $career, [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Parcours modifié avec succès !');

            // Redirection
            return $this->redirectToRoute('app_career', [
                'externalId' => $this->getUser()->getExternalId(),
                'id' => $career->getId()
            ]);
        }

        $userController->isVerified();

        // Template render
        return $this->render('career/update.html.twig', [
            'myForm' => $form->createView(),
            'career' => $career,
            'user' => $this->getUser(),
            'spectator' => false
        ]);
    }

    public function delete(Career $career, Request $request, UserController $userController) {
        if(!$career->isOwnedBy($this->getUser())) {
            throw $this->createNotFoundException();
        }

        $csrf_token = $request->request->get('csrf_token');
        if($this->isCsrfTokenValid('career_deletion_' . $career->getId(), $csrf_token)) {
            $this->getUser()->removeCareer($career);

            $this->em->remove($career);
            $this->em->flush();

            // Flash message
            $this->addFlash('info', 'Parcours supprimé avec succès !');
        }

        // Redirection
        return $this->redirectToRoute('app_career', [
            'externalId' => $this->getUser()->getExternalId()
        ]);
    }

    /**
     * @param User $offlineUser
     * @param Career $career
     * @param Request $request
     * @param \App\Controller\UserController $userController
     * @return RedirectResponse|Response
     * @ParamConverter("offlineUser", options={"mapping": {"externalId": "externalId"}})
     */
    public function review(User $offlineUser, Career $career, Request $request, UserController $userController): RedirectResponse|Response {
        $review = new Review;

        list($user, $spectator) = $userController->isSpectator($offlineUser);

        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Associate the review to the current career
            $career->addReview($review);

            $this->em->persist($review);
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Votre avis a bien été enregistré, merci !');

            // Redirection to the show page
            return $this->redirectToRoute('app_career', [
                'externalId' => $user->getExternalId()
            ]);
        }

        $userController->isVerified();

        // Template render
        return $this->render("review/create.html.twig", [
            'form' => $form->createView(),
            'user' => $user,
            'spectator' => $spectator,
            'career' => $career,
        ]);
    }
}
