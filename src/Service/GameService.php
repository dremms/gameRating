<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class GameService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Retourne les jeux disponibles pour un utilisateur.
     */
    public function getAvailableGamesForUser(User $user): array
    {
        return $this->em->getRepository(Game::class)
            ->createQueryBuilder('g')
            ->where('g.id NOT IN (
                SELECT IDENTITY(ug.game)
                FROM App\Entity\UserGame ug
                WHERE ug.user = :user
            )')
            ->setParameter('user', $user)
            ->orderBy('g.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
