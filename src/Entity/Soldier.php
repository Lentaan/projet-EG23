<?php

namespace App\Entity;

use App\Enum\Ai;
use App\Enum\Rank;
use App\Repository\SoldierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SoldierRepository::class)]
class Soldier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private int $strength = 0;

    #[ORM\Column]
    private int $dexterity = 0;

    #[ORM\Column]
    private int $constitution = 0;

    #[ORM\Column]
    private int $initiative = 0;

    #[ORM\Column]
    private int $resistance = 0;

    #[ORM\Column]
    private int $damage = 0;

    #[ORM\Column]
    private int $escape = 0;

    #[ORM\Column]
    private int $heal = 0;

    #[ORM\Column]
    private int $life = 30;

    #[ORM\Column]
    private int $shield = 0;

    #[ORM\Column]
    private int $precision = 0;

    #[ORM\Column]
    private bool $isReserved = false;

    #[ORM\Column(type: "ai", length: 255)]
    private Ai $ai = Ai::RANDOM;
    
    #[ORM\Column(type: "rank", length: 255)]
    private Rank $rank = Rank::NOOB;

    #[ORM\ManyToOne(inversedBy: 'soldiers')]
    private ?Player $player = null;

    #[ORM\ManyToOne(inversedBy: 'soldiersPlayer1')]
    private ?ZoneControl $zoneControl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStrength(): ?int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): static
    {
        $this->strength = $strength;

        return $this;
    }

    public function getDexterity(): ?int
    {
        return $this->dexterity;
    }

    public function setDexterity(int $dexterity): static
    {
        $this->dexterity = $dexterity;

        return $this;
    }

    public function getConstitution(): ?int
    {
        return $this->constitution;
    }

    public function setConstitution(int $constitution): static
    {
        $this->constitution = $constitution;

        return $this;
    }

    public function getInitiative(): ?int
    {
        return $this->initiative;
    }

    public function setInitiative(int $initiative): static
    {
        $this->initiative = $initiative;

        return $this;
    }

    public function getResistance(): ?int
    {
        return $this->resistance;
    }

    public function setResistance(int $resistance): static
    {
        $this->resistance = $resistance;

        return $this;
    }

    public function getDamage(): ?int
    {
        return $this->damage;
    }

    public function setDamage(int $damage): static
    {
        $this->damage = $damage;

        return $this;
    }

    public function getEscape(): ?int
    {
        return $this->escape;
    }

    public function setEscape(int $escape): static
    {
        $this->escape = $escape;

        return $this;
    }

    public function getHeal(): ?int
    {
        return $this->heal;
    }

    public function setHeal(int $heal): static
    {
        $this->heal = $heal;

        return $this;
    }

    public function getLife(): ?int
    {
        return $this->life;
    }

    public function setLife(int $life): static
    {
        $this->life = $life;

        return $this;
    }

    public function getShield(): ?int
    {
        return $this->shield;
    }

    public function setShield(int $shield): static
    {
        $this->shield = $shield;

        return $this;
    }

    public function getPrecision(): ?int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): static
    {
        $this->precision = $precision;

        return $this;
    }

    public function isIsReserved(): ?bool
    {
        return $this->isReserved;
    }

    public function setIsReserved(bool $isReserved): static
    {
        $this->isReserved = $isReserved;

        return $this;
    }

    public function getAi(): ?Ai
    {
        return $this->ai;
    }

    public function setAi(Ai $ai): static
    {
        $this->ai = $ai;

        return $this;
    }

    public function getRank(): ?Rank
    {
        return $this->rank;
    }

    public function setRank(Rank $rank): static
    {
        $this->rank = $rank;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getTotalPoints(): int
    {
        return $this->getStrength() + $this->getDexterity() + $this->getResistance() + $this->getConstitution() + $this->getInitiative();
    }

    public function getZoneControl(): ?ZoneControl
    {
        return $this->zoneControl;
    }

    public function setZoneControl(?ZoneControl $zoneControl): static
    {
        $this->zoneControl = $zoneControl;

        return $this;
    }
}
