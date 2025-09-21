<?php

namespace App\Controller;

use App\Repository\UserGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\StatsService;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(StatsService $statsService, UserGameRepository $userGameRepository): Response
    {
        $user = $this->getUser();

        if ($user === null) {
            $userStats = [];
            $globalStats = [];
        } else {
            $userStats   = $statsService->getUserStats($user);
            $globalStats = $statsService->getGlobalStats($user);
        }

        return $this->render('home/index.html.twig', [
            'controller_name'   => 'HomeController',
            'user_games'        => $userGameRepository->findBy(['user' => $user], ['playEndDate' => 'DESC'], 5),
            'user_games_everyone' => $userGameRepository->findBy([], ['playEndDate' => 'DESC'], 10),
            'ratingScaleLabel'  => $user->getRatingScale()->getName(),
            'userStats'         => $userStats,
            'globalStats'       => $globalStats,
        ]);
    }


}
