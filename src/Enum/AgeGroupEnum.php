<?php

declare(strict_types=1);

namespace App\Enum;

enum AgeGroupEnum: int
{
    case CHILD_3_5 = 5;
    case CHILD_6_11 = 11;
    case CHILD_12_17 = 17;

    public static function getGroup(int $age): string
    {
        if ($age <= self::CHILD_3_5->value) {
            return '3-5';
        }

        if ($age <= self::CHILD_6_11->value) {
            return '6-11';
        }

        if ($age <= self::CHILD_12_17->value) {
            return '12-17';
        }

        return '';
    }
}
