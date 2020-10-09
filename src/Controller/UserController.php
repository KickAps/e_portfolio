<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    private $user;

    public function __construct(Security $security)
    {
        // Get the current user
        $this->user = $security->getUser();
    }

    public function show()
    {
        return $this->render('user/show.html.twig', [
            'user' => $this->user
        ]);
    }

    public function update(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(UserType::class, $this->user, [
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
            'user' => $this->user
        ]);
    }
}
