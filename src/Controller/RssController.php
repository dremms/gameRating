<?php

namespace App\Controller;

use App\Repository\UserGameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RssController extends AbstractController
{

    #[Route('/rss', name: 'rss_feed')]
    public function rss(UserGameRepository $userGameRepository): Response
    {
        $userGameList = $userGameRepository->findBy([], ['playEndDate' => 'DESC'], 5);
        return $this->render('rss/feed.xml.twig', [
            'user_games_everyone' => $userGameList,
        ]);
    }

}