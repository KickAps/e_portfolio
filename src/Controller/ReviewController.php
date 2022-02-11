<?php

namespace App\Controller;

use App\Entity\Career;
use App\Entity\Review;
use App\Entity\User;
use App\Form\ReviewType;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ReviewController extends AbstractController {

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em) {
        // Initiate the entity manager
        $this->em = $em;
    }

    /**
     * @param User $offlineUser
     * @param UserController $userController
     * @param Career|null $career
     * @return Response
     *
     * @ParamConverter("offlineUser", options={"mapping": {"externalId": "externalId"}})
     * @ParamConverter("career", options={"mapping": {"id": "id"}}, isOptional="true")
     */
    public function index(User $offlineUser, UserController $userController, Career $career = null): Response {
        list($user, $spectator) = $userController->isSpectator($offlineUser);
        if($career) {
            $reviews = $career->getReviews();
        } else {
            $reviews = $user->getReviews();
        }


        $form = $this->createForm(ReviewType::class);

        // Template render
        return $this->render('review/index.html.twig', [
            'form' => $form->createView(),
            'reviews' => $reviews,
            'global' => $career === null,
            'user' => $user,
            'spectator' => $spectator
        ]);
    }
}
