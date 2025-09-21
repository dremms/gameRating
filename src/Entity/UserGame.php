<?php

namespace App\Entity;

use App\Repository\UserGameRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserGameRepository::class)]
class UserGame
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $playStartDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $playEndDate = null;

    #[ORM\Column]
    private ?bool $completedStory = null;

    #[ORM\Column]
    private ?bool $completedFull = null;

    #[ORM\Column]
    private ?bool $earlyAccess = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(nullable: true)]
    private ?int $scorePercent = null;

    #[ORM\ManyToOne(inversedBy: 'userGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userGames')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $playTimeSeconds = null;

    private ?int $displayPlayTimeHours = null;

    private ?int $displayPlayTimeMinutes = null;
    private ?int $displayPlayTimeSeconds = null;

    #[ORM\ManyToOne(inversedBy: 'userGame')]
    private ?Platform $platform = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $updateDate = null;


    public function getDisplayScore(): ?int
    {
        if ($this->scorePercent === null || !$this->user || !$this->user->getRatingScale()) {
            return null;
        }

        $scale = $this->user->getRatingScale()->getMaxScale();
        return (int) round(($this->scorePercent / 100) * $scale);
    }

    public function getDisplayScoreHome(): ?string
    {
        $score = $this->getDisplayScore();
        if ($score === null) {
            return null;
        }
        return $this->getDisplayScore() . ' / ' . $this->user->getRatingScale()->getMaxScale();
    }


    public function getPlayTimeSeconds(): ?int
    {
        return $this->playTimeSeconds;
    }

    private function setDisplayPlayTimeHours(): ?int
    {
        return $this->displayPlayTimeHours = floor($this->playTimeSeconds / 3600);
    }

    private function setDisplayPlayTimeMinutes(): ?int
    {
        return $this->displayPlayTimeMinutes = (($this->playTimeSeconds - ($this->displayPlayTimeHours * 3600)) / 60);
    }

    private function setDisplayPlayTimeSeconds(): ?int
    {
        return $this->displayPlayTimeSeconds = ($this->playTimeSeconds - ($this->displayPlayTimeHours * 3600) - ($this->displayPlayTimeMinutes * 60));
    }

    public function setPlayTimeSeconds(?int $seconds): self
    {
        $this->playTimeSeconds = $seconds;
        $this->setDisplayPlayTimeHours();
        $this->setDisplayPlayTimeMinutes();
        $this->setDisplayPlayTimeSeconds();
        return $this;
    }

    public function setPlayTimeFromHms(int $hours, int $minutes, int $seconds): void
    {
        $this->playTimeSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    /** Helper to format as H:i:s */
    public function getFormattedPlayTime(string $formatedType = ':'): ?string
    {
        if ($this->playTimeSeconds === null) {
            return null;
        }
        $hours = floor($this->playTimeSeconds / 3600);
        $minutes = floor(($this->playTimeSeconds % 3600) / 60);
        $seconds = $this->playTimeSeconds % 60;
        if ($formatedType === 'hm') {
            return sprintf('%02dh %02dmin', $hours, $minutes);
        }
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayStartDate(): ?\DateTime
    {
        return $this->playStartDate;
    }

    public function setPlayStartDate(?\DateTime $playStartDate): static
    {
        $this->playStartDate = $playStartDate;

        return $this;
    }

    public function getPlayEndDate(): ?\DateTime
    {
        return $this->playEndDate;
    }

    public function setPlayEndDate(?\DateTime $playEndDate): static
    {
        $this->playEndDate = $playEndDate;

        return $this;
    }

    public function isCompletedStory(): ?bool
    {
        return $this->completedStory;
    }

    public function setCompletedStory(bool $completedStory): static
    {
        $this->completedStory = $completedStory;

        return $this;
    }

    public function isCompletedFull(): ?bool
    {
        return $this->completedFull;
    }

    public function setCompletedFull(bool $completedFull): static
    {
        $this->completedFull = $completedFull;

        return $this;
    }

    public function getEarlyAccess(): ?bool
    {
        return $this->earlyAccess;
    }

    public function setEarlyAccess(?bool $earlyAccess): void
    {
        $this->earlyAccess = $earlyAccess;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getScorePercent(): ?int
    {
        return $this->scorePercent;
    }

    public function setScorePercent(?int $scorePercent): static
    {
        $this->scorePercent = $scorePercent;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    public function getPlatform(): ?Platform
    {
        return $this->platform;
    }

    public function setPlatform(?Platform $platform): static
    {
        $this->platform = $platform;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTime $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUpdateDate(): ?\DateTime
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTime $updateDate): static
    {
        $this->updateDate = $updateDate;

        return $this;
    }
}
