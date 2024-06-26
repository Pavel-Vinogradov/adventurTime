<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use App\Dto\DiscountDto;

final class ChildDiscountStrategy implements DiscountStrategy
{
    private const MAX_DISCOUNT_AMOUNT = 4500;
    private const CHILD_0_2_DISCOUNT = 1.0;  // 100% скидка
    private const CHILD_3_5_DISCOUNT = 0.8;  // 80% скидка
    private const CHILD_6_11_DISCOUNT = 0.3; // 30% скидка
    private const CHILD_12_15_DISCOUNT = 0.1; // 10% скидка

    public function calculateDiscount(DiscountDto $discountDto): float
    {
        $baseCost = $discountDto->initialPrice;
        $age = $this->calculateAge($discountDto->birthDate, $discountDto->startDate);

        if (3 > $age) {
            return round($baseCost * (1 - self::CHILD_0_2_DISCOUNT), 2);
        }

        if (6 > $age) {
            return round($baseCost * (1 - self::CHILD_3_5_DISCOUNT), 2);
        }

        if (12 > $age) {
            $discountedPrice = $baseCost * (1 - self::CHILD_6_11_DISCOUNT);

            return round(max($baseCost - self::MAX_DISCOUNT_AMOUNT, $discountedPrice), 2);
        }

        if (16 > $age) {
            return round($baseCost * (1 - self::CHILD_12_15_DISCOUNT), 2);
        }
         return $baseCost;
    }

    private function calculateAge(DateTimeImmutable $birthDate, DateTimeImmutable $travelStartDate): int
    {
        return $travelStartDate->diff($birthDate)->y;
    }
}
