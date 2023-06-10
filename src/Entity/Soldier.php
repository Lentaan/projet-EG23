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
    private ?int $strength = null;

    #[ORM\Column]
    private ?int $dexterity = null;

    #[ORM\Column]
    private ?int $constitution = null;

    #[ORM\Column]
    private ?int $initiative = null;

    #[ORM\Column]
    private ?int $resistance = null;

    #[ORM\Column]
    private ?int $damage = null;

    #[ORM\Column]
    private ?int $escape = null;

    #[ORM\Column]
    private ?int $heal = null;

    #[ORM\Column]
    private ?int $life = null;

    #[ORM\Column]
    private ?int $shield = null;

    #[ORM\Column]
    private ?int $precision = null;

    #[ORM\Column]
    private ?bool $isReserved = null;

    #[ORM\Column(type: "ai", length: 255)]
    private ?Ai $ai = null;
    
    #[ORM\Column(type: "rank", length: 255)]
    private ?Rank $rank = null;

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
}
