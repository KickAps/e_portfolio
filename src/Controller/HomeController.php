<?php

namespace App\Controller;

use App\Controller\UserController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(UserController $userController)
    {
        $userController->isVerified();

        // Template render
        return $this->render('home/index.html.twig', [
            'spectator' => false,
            'user' => $this->getUser()
        ]);
    }
}
