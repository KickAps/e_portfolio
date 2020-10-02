<?php

namespace App\Controller;

use App\Entity\Career;
use App\Repository\CareerRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CareerController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function index(ProfilRepository $profilRepository, CareerRepository $tmp)
    {
        // TODO : Get the current user
        $profil = $profilRepository->findAll()[0];

        $tmp_data = [];

        foreach ($tmp->findAll() as $career) {
            array_push($tmp_data, [
                $career->getTitle(),
                $career->getStartDate()->getTimestamp() * 1000,
                $career->getEndDate()->getTimestamp() * 1000
            ]);
        }

        $json_data = json_encode($tmp_data);

        // Template render
        return $this->render('career/index.html.twig', compact('profil', 'json_data'));
    }

    public function update(ProfilRepository $profilRepository)
    {
        $career = new Career;
        // TODO : Get the current user
        $profil = $profilRepository->findAll()[0];

        // Template render
        return $this->render('career/update.html.twig');
    }
}
