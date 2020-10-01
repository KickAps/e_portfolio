<?php

namespace App\Controller;

use App\Repository\ProfilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    public function index(ProfilRepository $profilRepository)
    {
        // TODO : Get the current user
        $profil = $profilRepository->findAll()[0];

        return $this->render('profil/index.html.twig', compact('profil'));
    }
}
