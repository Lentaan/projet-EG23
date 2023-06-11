<?php

namespace App\Enum;

use JsonSerializable;
enum Zone: string implements JsonSerializable
{
    case HS = "Halle Sportive";
    case BDE = "BDE";
    case LIBRARY = "Bibliothèque";
    case QA = "Quartier Administratif";
    case HI = "Halles Industrielles";


    public function jsonSerialize(): string
    {
        return $this->name;
    }
}
