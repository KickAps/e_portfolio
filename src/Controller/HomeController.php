<?php

namespace App\Controller;

use App\Controller\UserController;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index(User $offlineUser, UserController $userController)
    {
        list($user, $spectator) = $userController->isSpectator($offlineUser);

        return $this->render('home/index.html.twig', [
            'user' => $user,
            'spectator' => $spectator
        ]);
    }
}
