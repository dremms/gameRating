<?php

// src/Service/StatsService.php
namespace App\Service;

use App\Entity\UserGame;
use App\Repository\UserGameRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class StatsService
{
    private UserGameRepository $userGameRepository;
    public function __construct(UserGameRepository $userGameRepository)
    {
        $this->userGameRepository = $userGameRepository;
    }

    public function getUserStats(UserInterface $user): array
    {
        $today = new \DateTime();
        $userGame = new UserGame();

        $userGameStats = $this->userGameRepository->getStats($user);
        $userGOTY = $this->userGameRepository->getGameOfTheYearsBeforeDate($user, $today->format('Y-m-d'));

        if (empty($userGameStats)) {
            return [];
        }

        // Total playtime
        $userGame->setPlayTimeSeconds($userGameStats['totalPlayTimeSeconds']);

        // Average rating
        $stats = new UserGameStatsService($userGameStats['avgScore'], $user);
        $userAvgScore = $stats->getDisplayScoreHome();

        // GOTY
        $stats = new UserGameStatsService($userGOTY['scorePercent'], $user);
        $userGotyScore = $stats->getDisplayScoreHome();

        return [
            'averageRating' => $userAvgScore,
            'ratingsCount' => $userGameStats['ratingCount'],
            'totalPlayTime' => $userGame->getFormattedPlayTime('hm'),
            'bestGame' => $userGOTY['title'],
            'bestGameRating' => $userGotyScore,
        ];
    }

    public function getGlobalStats(UserInterface $user): array
    {
        $today = new \DateTime();
        $userGame = new UserGame();

        $globalStats = $this->userGameRepository->getStats();
        $mostPopular = $this->userGameRepository->getMostRatedGameBeforeDate($today->format('Y-m-d'));

        if (empty($globalStats)) {
            return [];
        }

        // Total playtime
        $userGame->setPlayTimeSeconds($globalStats['totalPlayTimeSeconds']);

        // Average rating (affiché selon la scale de l'utilisateur connecté)
        $stats = new UserGameStatsService($globalStats['avgScore'], $user);
        $globalAvgScore = $stats->getDisplayScoreHome();

        return [
            'averageRating' => $globalAvgScore,
            'ratingsCount' => $globalStats['ratingCount'],
            'totalPlayTime' => $userGame->getFormattedPlayTime('hm'),
            'mostPopularGame' => $mostPopular['title'],
        ];
    }
}
