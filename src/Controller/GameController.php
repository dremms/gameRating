<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Platform;
use App\Form\GameType;
use App\Repository\GameRepository;
use App\Repository\PlatformRepository;
use App\Service\IgdbClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/game')]
final class GameController extends AbstractController
{
    #[Route(name: 'app_game_index', methods: ['GET'])]
    public function index(GameRepository $gameRepository): Response
    {
        return $this->render('game/index.html.twig', [
            'games' => $gameRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_game_new', methods: ['GET', 'POST'])]
    public function new(
        Request                $request,
        IgdbClientService      $igdb,
        EntityManagerInterface $em,
        PlatformRepository     $platformRepo,
        GameRepository $gameRepository
    ): Response
    {
        $form = $this->createForm(GameType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $igdbId = $form->get('igdbId')->getData();

            //test si le jeu existe déjà
            $game = $gameRepository->findOneBy(['igdbId' => $igdbId]);
            if ($game) {
                $this->addFlash('error', 'Le jeu éxiste déjà');
                return $this->redirectToRoute('app_game_new');
            }

            //Récupération des infos du jeu
            $gameData = $igdb->request('games', "fields id, name, summary, storyline, first_release_date, platforms, cover, slug; where id={$igdbId}; limit 1;");
            if (!$gameData) {
                $this->addFlash('error', 'Jeu introuvable sur IGDB');
                return $this->redirectToRoute('app_game_new');
            }
            $gameData = $gameData[0];

            // Gestion des plateformes
            if (!empty($gameData['platforms'])) {
                $platformToAdd = [];
                foreach ($gameData['platforms'] as $platformId) {
                    $platform = $platformRepo->findOneBy(['igdbId' => $platformId]);
                    if (!$platform) {
                        $platformToAdd[] = $platformId;
                    }
                }

                if (!empty($platformToAdd)) {
                    $platformsData = $igdb->request('platforms', 'fields id, name, abbreviation; where id = (' . implode(',', $platformToAdd) . ');');
                    foreach ($platformsData as $pData) {
                        $platform = new Platform();
                        $platform->setIgdbId($pData['id']);
                        $platform->setName($pData['name']);
                        $platform->setAbbreviation($pData['abbreviation'] ?? null);
                        $em->persist($platform);
                    }
                }
                $em->flush();
            }

            //Récupération ou création de l’entité Game
            $game = new Game();
            $game->setIgdbId($gameData['id']);
            $game->setTitle($gameData['name']);
            $game->setSummary($gameData['summary'] ?? null);
            $game->setStoryline($gameData['storyline'] ?? null);
            if (!empty($gameData['first_release_date'])) {
                $game->setReleaseDate((new \DateTime())->setTimestamp($gameData['first_release_date']));
            }

            // Gestion de la cover
            if (isset($gameData['cover'])) {
                $coverData = $igdb->request('covers', "fields url; where id={$gameData['cover']};");
                $coverUrl = 'https:' . str_replace('/t_thumb/', '/t_cover_big/', $coverData[0]['url']);
                $filename = $gameData['slug'] . '.jpg';
                $newCoverImagePath = $this->getParameter('games_images_directory') . '/' . $filename;
                $game->setCoverImage($filename);
                $coverImageData = @file_get_contents($coverUrl);
                file_put_contents($newCoverImagePath, $coverImageData);
            }

            // jointure platform game
            if (!empty($gameData['platforms'])) {
                foreach ($gameData['platforms'] as $platformId) {
                    $platform = $platformRepo->findOneBy(['igdbId' => $platformId]);
                    $game->addPlatform($platform);
                }
            }

            $em->persist($game);
            $em->flush();

            $this->addFlash('success', "Jeu « {$game->getTitle()} » ajouté avec succès ✅");

            return $this->redirectToRoute('app_game_index');
        }

        return $this->render('game/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_game_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/{id}', name: 'app_game_delete', methods: ['POST'])]
    public function delete(Request $request, Game $game, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $game->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_game_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/test', name: 'igdb_test')]
    public function test(IgdbClientService $igdb): Response
    {
        $game = $igdb->request('games', 'fields id, name, summary, storyline, first_release_date, platforms, cover; where id=103329; limit 1;');
        $coverId = $game[0]['cover'];
        $platforms = $game[0]['platforms'];
        $cover = $igdb->request('covers', "fields *; where id=$coverId; limit 1;");
        $imageData = @file_get_contents('https:' . str_replace('/t_thumb/', '/t_cover_big/', $cover[0]['url']));
        dd($game, $cover, $platforms, $imageData);
    }
}
