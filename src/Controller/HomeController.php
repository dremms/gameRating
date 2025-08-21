<?php

namespace App\Controller;

use App\Repository\UserGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(UserGameRepository $userGameRepository): Response
    {
        $user = $this->getUser();
        $ratingScaleLabel = $user->getRatingScale()->getName();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user_games' => $userGameRepository->findBy(['user' => $user], ['playEndDate' => 'DESC'], 5),
            'user_games_everyone' => $userGameRepository->findBy([], ['playEndDate' => 'DESC'], 10),
            'ratingScaleLabel' => $ratingScaleLabel,
        ]);
    }
}
