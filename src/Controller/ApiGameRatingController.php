<?php

namespace App\Controller;

use App\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
final class ApiGameRatingController extends AbstractController
{

    #[Route('/game/{id}/platforms', name: 'api_game_platforms', methods: ['GET'])]
    public function getPlatforms(Game $game): JsonResponse
    {
        $platforms = $game->getPlatforms()->map(fn($p) => [
            'id' => $p->getId(),
            'name' => $p->getName(),
        ])->toArray();

        return $this->json($platforms, 200, [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

}
