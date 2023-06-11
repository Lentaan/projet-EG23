<?php

namespace App\Entity;

use App\Enum\Speciality;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $total_points = 400;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    // "speciality" corresponds to the name defined in StatusType::getName()
    #[ORM\Column(type: "speciality", length: 255)]
    private ?Speciality $speciality = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Soldier::class)]
    private Collection $soldiers;

    #[ORM\ManyToOne(inversedBy: 'players')]
    private ?Game $game = null;

    public function __construct()
    {
        $this->soldiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getTotalPoints(): ?int
    {
        return $this->total_points;
    }

    public function setTotalPoints(int $total_points): void
    {
        $this->total_points = $total_points;
    }

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(Speciality $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * @return Collection<int, Soldier>
     */
    public function getSoldiers(): Collection
    {
        return $this->soldiers;
    }

    public function addSoldier(Soldier $soldier): static
    {
        if (!$this->soldiers->contains($soldier)) {
            $this->soldiers->add($soldier);
            $soldier->setPlayer($this);
        }

        return $this;
    }

    public function removeSoldier(Soldier $soldier): static
    {
        if ($this->soldiers->removeElement($soldier)) {
            // set the owning side to null (unless already changed)
            if ($soldier->getPlayer() === $this) {
                $soldier->setPlayer(null);
            }
        }

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

    public function getNbReservedSoldier() {
        /* @var $i Soldier */
        return $this->getSoldiers()->reduce(fn ($c,$i) => ($i->isIsReserved() ? $c + 1: $c), 0);
    }
}
