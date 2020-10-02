<?php

namespace App\Controller;

use App\Entity\Career;
use App\Form\CareerType;
use App\Repository\CareerRepository;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

        $range_data = [];
        $moment_data = [];

        foreach ($profil->getCareer() as $career)
        {
            if ($career->getEndDate())
            {
                array_push($range_data, [
                    $career->getTitle(),
                    $career->getStartDate()->getTimestamp() * 1000,
                    $career->getEndDate()->getTimestamp() * 1000
                ]);
            }
            else
            {
                array_push($moment_data, [
                    $career->getStartDate()->getTimestamp() * 1000,
                    $career->getTitle()
                ]);
            }
        }

        $json_range_data = json_encode($range_data);
        $json_moment_data = json_encode($moment_data);

        // Template render
        return $this->render('career/index.html.twig', compact('profil', 'json_range_data', 'json_moment_data'));
    }

    public function create(Request $request, ProfilRepository $profilRepository)
    {
        $career = new Career;
        // TODO : Get the current user
        $profil = $profilRepository->findAll()[0];

        $form = $this->createForm(CareerType::class, $career);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Associate the project to the current profil
            $profil->addCareer($career);

            $this->em->persist($career);
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Parcours créé avec succés !');

            // Redirection to the show page
            return $this->redirectToRoute('app_career');
        }

        // Template render
        return $this->render('career/create.html.twig', [
            'myForm' => $form->createView()
        ]);
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
