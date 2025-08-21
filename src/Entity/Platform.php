<?php

namespace App\Entity;

use App\Repository\PlatformRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlatformRepository::class)]
class Platform
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $abbreviation = null;

    #[ORM\Column]
    private ?int $igdbId = null;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\ManyToMany(targetEntity: Game::class, mappedBy: 'platforms')]
    private Collection $games;

    /**
     * @var Collection<int, UserGame>
     */
    #[ORM\OneToMany(targetEntity: UserGame::class, mappedBy: 'platform')]
    private Collection $userGame;

    public function __construct()
    {
        $this->games = new ArrayCollection();
        $this->userGame = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(?string $abbreviation): static
    {
        $this->abbreviation = $abbreviation;

        return $this;
    }

    public function getIgdbId(): ?int
    {
        return $this->igdbId;
    }

    public function setIgdbId(int $igdbId): static
    {
        $this->igdbId = $igdbId;

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    /**
     * @return Collection<int, UserGame>
     */
    public function getUserGame(): Collection
    {
        return $this->userGame;
    }

    public function addUserGame(UserGame $userGame): static
    {
        if (!$this->userGame->contains($userGame)) {
            $this->userGame->add($userGame);
            $userGame->setPlatform($this);
        }

        return $this;
    }

    public function removeUserGame(UserGame $userGame): static
    {
        if ($this->userGame->removeElement($userGame)) {
            // set the owning side to null (unless already changed)
            if ($userGame->getPlatform() === $this) {
                $userGame->setPlatform(null);
            }
        }

        return $this;
    }
}
