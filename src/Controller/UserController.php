<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{
    private $user;

    public function __construct(Security $security)
    {
        // Get the current user
        $this->user = $security->getUser();
    }

    public function show()
    {
        return $this->render('user/show.html.twig', [
            'user' => $this->user
        ]);
    }

    public function update()
    {
        return $this->render('user/update.html.twig', [
            'user' => $this->user
        ]);
    }
}
