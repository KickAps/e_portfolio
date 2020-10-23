<?php

namespace App\Controller;

use App\Controller\UserController;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function index(string $externalId, UserController $userController, UserRepository $userRepository)
    {
        $offlineUser = $userRepository->findOneBy(['externalId' => $externalId]);

        list($user, $spectator) = $userController->isSpectator($offlineUser);

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'spectator' => $spectator
        ]);
    }

    public function show()
    {
        return $this->render('user/show.html.twig', [
            'user' => $this->getUser(),
            'spectator' => false
        ]);
    }

    public function update(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, $this->getUser(), [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();

            // Flash message
            $this->addFlash('success', 'Informations modifiées avec succés !');

            // Redirection
            return $this->redirectToRoute('app_user_show', [
                'externalId' => $this->getUser()->getExternalId()
            ]);
        }

        // Template render
        return $this->render('user/update.html.twig', [
            'myForm' => $form->createView(),
            'user' => $this->getUser(),
            'spectator' => false
        ]);
    }

    public function isSpectator($offlineUser)
    {
        $spectator = false;
        if(!$user = $this->getUser())
        {
            $user = $offlineUser;
            $spectator = true;
        }
        return [$user, $spectator];
    }
}
