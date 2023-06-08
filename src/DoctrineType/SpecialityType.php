<?php

namespace App\DoctrineType;

use App\Enum\Speciality;

class SpecialityType extends AbstractEnumType
{
    public const NAME = "speciality";

    public static function getEnumsClass(): string
    {
        return Speciality::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}