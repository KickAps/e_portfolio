<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function index(UserRepository $userRepository)
    {
        // TODO : Get the current user
        $user = $userRepository->findAll()[0];

        return $this->render('user/index.html.twig', compact('user'));
    }
}
