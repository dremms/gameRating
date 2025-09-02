<?php

namespace App\Controller;

use App\Entity\Platform;
use App\Entity\UserGame;
use App\Form\UserGameType;
use App\Repository\UserGameRepository;
use App\Service\UserGameService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\GameService;

#[Route('/user/game')]
final class UserGameController extends AbstractController
{
    #[Route(name: 'app_user_game_index', methods: ['GET'])]
    public function index(UserGameRepository $userGameRepository, Request $request): Response
    {
        $user = $this->getUser();
        $ratingScaleLabel = $user->getRatingScale()->getName();

        $sort = $request->query->get('sort', 'playEndDate');
        $dir = $request->query->get('dir', 'desc');
        $filterStartDate = $request->query->get('filterStartDate') ?? date('Y-m-d', strtotime('-1 years'));
        $filterEndDate = $request->query->get('filterEndDate') ?? date( 'Y-m-d');

        $userGames = $userGameRepository->findByUserSorted($user, $sort, $dir, $filterStartDate, $filterEndDate);

        return $this->render('user_game/index.html.twig', [
            'user_games' => $userGames,
            'ratingScaleLabel' => $ratingScaleLabel,
            'sort' => $sort,
            'dir' => $dir,
            'filterStartDate' => $filterStartDate,
            'filterEndDate' => $filterEndDate,
        ]);
    }

    #[Route('/new', name: 'app_user_game_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, GameService $gameService, UserGameService $userGameService): Response
    {
        $user = $this->getUser();
        $ratingScaleLabel = $user->getRatingScale()->getName();

        $userGame = new UserGame();
        $games = $gameService->getAvailableGamesForUser($user);

        $form = $this->createForm(
            UserGameType::class,
            $userGame,
            ['ratingScaleLabel' => $ratingScaleLabel, 'currentUser' => $user, 'games' => $games, 'platforms' => $entityManager->getRepository(Platform::class)->findAll()]
        );
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->getTimePlayAndPlatform($userGame, $form, $entityManager);

            $userGame->setUser($user);
            $userGame->setCreationDate(new \DateTime('now'));
            $userGame->setUpdateDate(new \DateTime('now'));

            $rawScore = (int) $form->get('scorePercent')->getData();
            $userGameService->calculateScorePercent($userGame, $rawScore);

            $entityManager->persist($userGame);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_game/new.html.twig', [
            'user_game' => $userGame,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_game_show', methods: ['GET'])]
    public function show(UserGame $userGame): Response
    {
        $user = $this->getUser();
        $ratingScaleLabel = $userGame->getUser()->getRatingScale()->getName();

        return $this->render('user_game/show.html.twig', [
            'user_game' => $userGame,
            'ratingScaleLabel' => $ratingScaleLabel,
            'currentUser' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_game_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserGame $userGame, EntityManagerInterface $entityManager, UserGameService $userGameService): Response
    {
        $this->denyAccessUnlessGranted('USERGAME_EDIT', $userGame);
        $user = $this->getUser();
        $ratingScaleLabel = $user->getRatingScale()->getName();

        $hours = $minutes = $seconds = 0;
        if ($userGame->getPlayTimeSeconds() !== null) {
            $hours = floor($userGame->getPlayTimeSeconds() / 3600);
            $minutes = floor(($userGame->getPlayTimeSeconds() % 3600) / 60);
            $seconds = $userGame->getPlayTimeSeconds() % 60;
        }

        $game = $userGame->getGame();
        $platforms = $game->getPlatforms() ?? [];
        $form = $this->createForm(
            UserGameType::class,
            $userGame,
            [
                'ratingScaleLabel' => $ratingScaleLabel,
                'currentUser' => $user,
                'disableGame' => true,
                'games' => [$game],
                'dataHours' => $hours,
                'dataMinutes' => $minutes,
                'dataSeconds' => $seconds,
                'scorePercent' => $userGame->getDisplayScore(),
                'platforms' => $platforms,
                'currentPlatform' => $userGame->getPlatform(),
            ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getTimePlayAndPlatform($userGame, $form, $entityManager);

            $rawScore = (int) $form->get('scorePercent')->getData();
            $userGameService->calculateScorePercent($userGame, $rawScore);
            $userGame->setUpdateDate(new \DateTime('now'));
            $entityManager->flush();

            return $this->redirectToRoute('app_user_game_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_game/edit.html.twig', [
            'user_game' => $userGame,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_game_delete', methods: ['POST'])]
    public function delete(Request $request, UserGame $userGame, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('USERGAME_DELETE', $userGame);
        if ($this->isCsrfTokenValid('delete' . $userGame->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($userGame);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_game_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @param UserGame $userGame
     * @param \Symfony\Component\Form\FormInterface $form
     * @param EntityManagerInterface $entityManager
     * @return void
     */
    private function getTimePlayAndPlatform(UserGame $userGame, \Symfony\Component\Form\FormInterface $form, EntityManagerInterface $entityManager): void
    {
        $userGame->setPlayTimeFromHms(
            (int)$form->get('hours')->getData(),
            (int)$form->get('minutes')->getData(),
            (int)$form->get('seconds')->getData()
        );

        $selectedPlatformId = $form->get('platform')->getData();
        if ($selectedPlatformId) {
            $platform = $entityManager->getRepository(Platform::class)->find($selectedPlatformId);
            if ($platform) {
                $userGame->setPlatform($platform);
            }
        }
    }
}
