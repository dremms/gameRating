<?php

namespace App\Service;

use App\Entity\Game;
use App\Entity\User;
use App\Entity\UserGame;
use Doctrine\ORM\EntityManagerInterface;

class UserGameService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function calculateScorePercent(UserGame $userGame, int $rawScore): void
    {
        $scale = $userGame->getUser()->getRatingScale()->getMaxScale();
        $userGame->setScorePercent(($rawScore / $scale) * 100);
    }

}
