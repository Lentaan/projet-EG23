<?php

namespace App\Enum;

use JsonSerializable;

enum Rank: int implements JsonSerializable
{
    case NOOB = 0;
    case ELITE = 1;
    case MASTER = 2;

    public function getName(): string
    {
        return match ($this) {
            Rank::NOOB => "NOOB",
            Rank::ELITE => "ÉLITE",
            Rank::MASTER => "MAÎTRE DE GUERRE",
        };
    }

    function getImageFromRank($width=""): string
    {
        return match($this) {
            Rank::NOOB => "<img class='img-rank' width='$width' height='$width' src='assets/images/noob_yellow.png' alt='Noob' />",
            Rank::ELITE => "<img class='img-rank' width='$width' height='$width' src='assets/images/elite_yellow.png' alt='Elite' />",
            Rank::MASTER => "<img class='img-rank' width='$width' height='$width' src='assets/images/master_yellow.png' alt='MdG' />"
        };
    }

    public function jsonSerialize(): string
    {
        return $this->getName();
    }
}