<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function show()
    {
        return $this->render('user/show.html.twig', [
            'user' => $this->getUser()
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
            return $this->redirectToRoute('app_user_show');
        }

        // Template render
        return $this->render('user/update.html.twig', [
            'myForm' => $form->createView(),
            'user' => $this->getUser()
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
