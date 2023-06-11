<?php

namespace App\DoctrineType;

use App\Enum\Rank;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class RankType extends AbstractEnumType
{
    public const NAME = "rank";

    public static function getEnumsClass(): string
    {
        return Rank::class;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof \BackedEnum) {
            return $value->value;
        } elseif (true === enum_exists($this->getEnumsClass(), true)) {
            return $value;
        }
        return null;
    }
    public function getName(): string
    {
        return self::NAME;
    }
}