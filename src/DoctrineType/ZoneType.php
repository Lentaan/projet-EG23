<?php

namespace App\DoctrineType;

use App\Enum\Zone;

class ZoneType extends AbstractEnumType
{
    public const NAME = "zone";

    public static function getEnumsClass(): string
    {
        return Zone::class;
    }


    public function getName(): string
    {
        return self::NAME;
    }
}