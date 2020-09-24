<?php

namespace App\Controller;

use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function index(ProfilRepository $repo)
    {
        $profil = $repo->findAll()[0];
        return $this->render('home/index.html.twig', compact('profil'));
    }
}
