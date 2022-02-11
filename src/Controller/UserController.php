<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController {
    private $em;

    public function __construct(EntityManagerInterface $em) {
        // Initiate the entity manager
        $this->em = $em;
    }

    public function index(User $offlineUser) {
        list($user, $spectator) = $this->isSpectator($offlineUser);

        $this->isVerified();

        // Template render
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'spectator' => $spectator
        ]);
    }

    public function update(Request $request) {
        $form = $this->createForm(UserType::class, $this->getUser(), [
            'method' => 'PUT'
        ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            // Flash message
            $this->addFlash('success', 'Informations modifiées avec succès !');

            // Redirection
            return $this->redirectToRoute('app_user', [
                'externalId' => $this->getUser()->getExternalId()
            ]);
        }

        $this->isVerified();

        // Template render
        return $this->render('user/update.html.twig', [
            'myForm' => $form->createView(),
            'user' => $this->getUser(),
            'spectator' => false
        ]);
    }

    public function isSpectator($offlineUser) {
        $spectator = false;
        if(!$user = $this->getUser()) {
            $user = $offlineUser;
            $spectator = true;
        }
        return [$user, $spectator];
    }

    public function isVerified() {
        if($this->getUser() and !$this->getUser()->isVerified()) {
            // Flash message
            $this->addFlash('warning', 'Veuillez vérifier votre adresse mail.');
        }
    }

    public function avatarUpload(Request $request) {
        $filename = $this->getUser()->getHashEmail() . '.jpg';
        $request->files->get('croppedImage')->move($this->getParameter('avatars_dir'), $filename);

        $this->getUser()->setAvatar($filename);
        $this->em->flush();

        return new Response();
    }
}
