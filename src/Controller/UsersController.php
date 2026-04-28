<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\StatsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users')]
    public function index(StatsService $statsService, UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $usersWithStats = [];
        foreach ($users as $user) {
            $usersWithStats[] = [
                'user' => $user,
                'stats' => $statsService->getUserStats($user),
            ];
        }
        //order by total play time desc
        usort($usersWithStats, fn($a, $b) => ($b['stats']['totalPlayTimeSeconds'] ?? 0) <=> ($a['stats']['totalPlayTimeSeconds'] ?? 0));

        return $this->render('users/index.html.twig', [
            'usersWithStats' => $usersWithStats,
        ]);
    }
}
