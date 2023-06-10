<?php

namespace App\DoctrineType;

use App\Enum\Rank;

class RankType extends AbstractEnumType
{
    public const NAME = "rank";

    public static function getEnumsClass(): string
    {
        return Rank::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}