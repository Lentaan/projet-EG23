<?php

namespace App\Enum;

enum Speciality : string {
    case RT = 'RT';
    case ISI = 'ISI';
    case GM = 'GM';
    case GI = 'GI';
    case MTE = 'MTE';
    case MM = 'MM';
    case A2I = 'A2I';

    public static function getAllNames(): array {
        return array_map(fn($case) => $case->value, Speciality::cases());
    }
}