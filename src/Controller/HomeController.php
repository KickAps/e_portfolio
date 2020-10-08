<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function index(UserRepository $repo)
    {
        $user = $repo->findAll()[0];
        return $this->render('home/index.html.twig', compact('user'));
    }
}
