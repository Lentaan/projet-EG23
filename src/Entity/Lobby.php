<?php

namespace App\Entity;

use App\Enum\Speciality;
use App\Repository\LobbyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LobbyRepository::class)]
class Lobby
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbPlayer = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: "speciality", length: 255)]
    private ?Speciality $speciality = null;

    public function getSpeciality(): ?Speciality
    {
        return $this->speciality;
    }

    public function setSpeciality(Speciality $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPlayer(): ?int
    {
        return $this->nbPlayer;
    }

    public function setNbPlayer(int $nbPlayer): static
    {
        $this->nbPlayer = $nbPlayer;

        return $this;
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
}
