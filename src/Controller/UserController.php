<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function index(string $externalId, UserRepository $userRepository)
    {
        $offlineUser = $userRepository->findOneBy(['externalId' => $externalId]);

        list($user, $spectator) = $this->isSpectator($offlineUser);

        $this->isVerified();

        // Template render
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'spectator' => $spectator
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
            $this->addFlash('success', 'Informations modifiées avec succès !');

            // Redirection
            return $this->redirectToRoute('app_user', [
                'externalId' => $this->getUser()->getExternalId()
            ]);
        }

        $this->isVerified();

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
        if (!$user = $this->getUser())
        {
            $user = $offlineUser;
            $spectator = true;
        }
        return [$user, $spectator];
    }

    public function isVerified()
    {
        if ($this->getUser() and !$this->getUser()->isVerified())
        {
            // Flash message
            $this->addFlash('warning', 'Veuillez vérifier votre adresse mail.');
        }
    }
}
