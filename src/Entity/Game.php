<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    public const MAX_SOLDIER = 20;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: Player::class)]
    private Collection $players;

    #[ORM\OneToMany(mappedBy: 'game', targetEntity: ZoneControl::class)]
    private Collection $zoneControls;

    public function __construct()
    {
        $this->players = new ArrayCollection();
        $this->zoneControls = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
            $player->setGame($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->players->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getGame() === $this) {
                $player->setGame(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ZoneControl>
     */
    public function getZoneControls(): Collection
    {
        return $this->zoneControls;
    }

    public function addZoneControl(ZoneControl $zoneControl): static
    {
        if (!$this->zoneControls->contains($zoneControl)) {
            $this->zoneControls->add($zoneControl);
            $zoneControl->setGame($this);
        }

        return $this;
    }

    public function removeZoneControl(ZoneControl $zoneControl): static
    {
        if ($this->zoneControls->removeElement($zoneControl)) {
            // set the owning side to null (unless already changed)
            if ($zoneControl->getGame() === $this) {
                $zoneControl->setGame(null);
            }
        }

        return $this;
    }

    public function getNbZoneControlled(Player $player) {
        /* @var ZoneControl $i */
        return $this->getZoneControls()->reduce(fn ($c,$i) => ($i->isIsControlled() && ($player === $i->getControllingPlayer())) ? $c + 1 : $c, 0);
    }

    public function getNbSoldiersPlaced() {
        /* @var ZoneControl $i */
        return $this->getZoneControls()->reduce(fn ($c,$i) => $c + $i->getSoldiersPlayer1()->count(), 0);
    }


    public function getUncontrolledZones(Player $player) {
        /* @var ZoneControl $p */
        return $this->getZoneControls()->filter(fn ($p) => !$p->isIsControlled());
    }


    public function getNoSoldierZones(Player $player) {
        /* @var ZoneControl $p */
        return $this->getZoneControls()->filter(fn ($p) => !!$p->getSoldiersPlayer1()->count());
    }
}
