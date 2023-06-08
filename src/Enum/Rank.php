<?php

namespace App\Enum;

use JsonSerializable;

enum Rank implements JsonSerializable
{
    case NOOB;
    case ELITE;
    case MASTER;

    public function getName(): string
    {
        return match ($this) {
            Rank::NOOB => "NOOB",
            Rank::ELITE => "ÉLITE",
            Rank::MASTER => "MAÎTRE DE GUERRE",
        };
    }

    public function jsonSerialize(): string
    {
        return $this->name();
    }
}