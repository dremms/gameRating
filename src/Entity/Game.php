<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $releaseDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverImage = null;

    /**
     * @var Collection<int, UserGame>
     */
    #[ORM\OneToMany(targetEntity: UserGame::class, mappedBy: 'game')]
    private Collection $userGames;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $storyline = null;

    #[ORM\Column]
    private ?int $igdbId = null;

    /**
     * @var Collection<int, Platform>
     */
    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'games')]
    private Collection $platforms;

    public function __construct()
    {
        $this->userGames = new ArrayCollection();
        $this->platforms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTime $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): static
    {
        $this->coverImage = $coverImage;

        return $this;
    }

    /**
     * @return Collection<int, UserGame>
     */
    public function getUserGames(): Collection
    {
        return $this->userGames;
    }

    public function addUserGame(UserGame $userGame): static
    {
        if (!$this->userGames->contains($userGame)) {
            $this->userGames->add($userGame);
            $userGame->setGame($this);
        }

        return $this;
    }

    public function removeUserGame(UserGame $userGame): static
    {
        if ($this->userGames->removeElement($userGame)) {
            // set the owning side to null (unless already changed)
            if ($userGame->getGame() === $this) {
                $userGame->setGame(null);
            }
        }

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }

    public function getStoryline(): ?string
    {
        return $this->storyline;
    }

    public function setStoryline(?string $storyline): static
    {
        $this->storyline = $storyline;

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
     * @return Collection<int, Platform>
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): static
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms->add($platform);
        }

        return $this;
    }

    public function removePlatform(Platform $platform): static
    {
        $this->platforms->removeElement($platform);

        return $this;
    }
}
