<?php

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;

class UserGameStatsService
{
    public function __construct(
        private readonly ?float $scorePercent,
        private readonly ?UserInterface $user,
    ) {}

    public function getDisplayScore(): ?float
    {
        if ($this->scorePercent === null || !$this->user || !$this->user->getRatingScale()) {
            return null;
        }

        $scale = $this->user->getRatingScale()->getMaxScale();
        return round(($this->scorePercent / 100) * $scale, 1);
    }

    public function getDisplayScoreHome(): ?string
    {
        $score = $this->getDisplayScore();
        if ($score === null) {
            return null;
        }
        return $score . ' / ' . $this->user->getRatingScale()->getMaxScale();
    }
}
