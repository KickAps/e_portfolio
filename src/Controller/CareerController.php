<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CareerController extends AbstractController
{
    public function index()
    {
        return $this->render('career/index.html.twig', [
            'controller_name' => 'CareerController',
        ]);
    }
}
