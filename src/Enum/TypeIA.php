<?php

namespace App\Enum;

use JsonSerializable;
enum TypeIA: string implements JsonSerializable
{
    case SHIELDING = "Défensif";
    case STRIKING = "Attaquant";
    case RANDOM = "Aléatoire";


    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
