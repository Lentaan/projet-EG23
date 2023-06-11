<?php

namespace App\Entity;

use App\Enum\Zone;
use App\Repository\ZoneControlRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneControlRepository::class)]
class ZoneControl
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'zoneControls')]
    private ?Game $game = null;

    #[ORM\OneToMany(mappedBy: 'zoneControl', targetEntity: Soldier::class)]
    private Collection $soldiersPlayer1;

    #[ORM\OneToMany(mappedBy: 'zoneControl', targetEntity: Soldier::class)]
    private Collection $soldiersPlayer2;

    #[ORM\Column(type: "zone", length: 255)]
    private ?Zone $zone = null;

    #[ORM\Column]
    private ?bool $isControlled = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Player $controllingPlayer = null;

    public function __construct()
    {
        $this->soldiersPlayer1 = new ArrayCollection();
        $this->soldiersPlayer2 = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Soldier>
     */
    public function getSoldiersPlayer1(): Collection
    {
        return $this->soldiersPlayer1;
    }

    public function addSoldiersPlayer1(Soldier $soldiersPlayer1): static
    {
        if (!$this->soldiersPlayer1->contains($soldiersPlayer1)) {
            $this->soldiersPlayer1->add($soldiersPlayer1);
            $soldiersPlayer1->setZoneControl($this);
        }

        return $this;
    }

    public function removeSoldiersPlayer1(Soldier $soldiersPlayer1): static
    {
        if ($this->soldiersPlayer1->removeElement($soldiersPlayer1)) {
            // set the owning side to null (unless already changed)
            if ($soldiersPlayer1->getZoneControl() === $this) {
                $soldiersPlayer1->setZoneControl(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Soldier>
     */
    public function getSoldiersPlayer2(): Collection
    {
        return $this->soldiersPlayer2;
    }

    public function addSoldiersPlayer2(Soldier $soldiersPlayer2): static
    {
        if (!$this->soldiersPlayer2->contains($soldiersPlayer2)) {
            $this->soldiersPlayer2->add($soldiersPlayer2);
            $soldiersPlayer2->setZoneControl($this);
        }

        return $this;
    }

    public function removeSoldiersPlayer2(Soldier $soldiersPlayer2): static
    {
        if ($this->soldiersPlayer2->removeElement($soldiersPlayer2)) {
            // set the owning side to null (unless already changed)
            if ($soldiersPlayer2->getZoneControl() === $this) {
                $soldiersPlayer2->setZoneControl(null);
            }
        }

        return $this;
    }

    public function isIsControlled(): ?bool
    {
        return $this->isControlled;
    }

    public function setIsControlled(bool $isControlled): static
    {
        $this->isControlled = $isControlled;

        return $this;
    }

    public function getControllingPlayer(): ?Player
    {
        return $this->controllingPlayer;
    }

    public function setControllingPlayer(?Player $controllingPlayer): static
    {
        $this->controllingPlayer = $controllingPlayer;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): void
    {
        $this->zone = $zone;
    }
}
