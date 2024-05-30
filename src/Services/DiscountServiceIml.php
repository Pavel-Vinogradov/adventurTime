<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use App\Dto\DiscountDto;

final class DiscountServiceIml implements DiscountService
{
    private const MAX_DISCOUNT_AMOUNT = 4500;
    private const DISCOUNT_PERCENTAGES = [
        'CHILD_3_5' => 0.8,
        'CHILD_6_11' => 0.3,
        'CHILD_12' => 0.1,
    ];

    public function calculateDiscount(DiscountDto $discountDto): float
    {
        $baseCost = $discountDto->initialPrice;
        $discountedCost = $baseCost;

        $birthDate = $discountDto->birthDate;
        $age = $this->calculateAge($birthDate);
        if (18 > $age) {
            $discountPercentage = $this->getDiscountPercentage($age);
            $discount = min(self::MAX_DISCOUNT_AMOUNT, $baseCost * $discountPercentage);
            $discountedCost -= $discount;
        }

        return $discountedCost;
    }

    private function calculateAge(DateTimeImmutable $birthDate): int
    {
        $currentDate = new DateTimeImmutable();

        return $currentDate->diff($birthDate)->y;
    }

    private function getDiscountPercentage(int $age): float
    {
        if (3 <= $age && 6 > $age) {
            return self::DISCOUNT_PERCENTAGES['CHILD_3_5'];
        }

        if (6 <= $age && 12 > $age) {
            return self::DISCOUNT_PERCENTAGES['CHILD_6_11'];
        }

        return self::DISCOUNT_PERCENTAGES['CHILD_12'];
    }
}
