<?php

namespace App\DoctrineType;

use App\Enum\Ai;

class AiType extends AbstractEnumType
{
    public const NAME = "ai";

    public static function getEnumsClass(): string
    {
        return Ai::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}