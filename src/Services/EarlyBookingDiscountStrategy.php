<?php

declare(strict_types=1);

namespace App\Services;

use DateTimeImmutable;
use App\Dto\DiscountDto;

final class EarlyBookingDiscountStrategy implements DiscountStrategy
{
    private const MAX_DISCOUNT_AMOUNT = 1500;

    public function calculateDiscount(DiscountDto $discountDto): float
    {
        $discountRate = $this->getDiscountRate($discountDto->startDate, $discountDto->paymentDate);
        $discountAmount = $discountDto->initialPrice * $discountRate;
        $discountedPrice = $discountDto->initialPrice - min($discountAmount, self::MAX_DISCOUNT_AMOUNT);

        return round($discountedPrice, 2);
    }

    private function getDiscountRate(DateTimeImmutable $startDate, DateTimeImmutable $paymentDate): float
    {
        $startMonth = (int)$startDate->format('n');
        $startYear = (int)$startDate->format('Y');
        $paymentMonth = (int)$paymentDate->format('n');
        $paymentYear = (int)$paymentDate->format('Y');
        $isTravelDateInRange = $startMonth >= 10 || $startMonth <= 1;

        if ($isTravelDateInRange && $paymentYear === $startYear && $paymentMonth <= 3) {
            return 0.07;
        }
        if ($isTravelDateInRange && $paymentMonth === 4 && $paymentYear === $startYear) {
            return 0.05;
        }
        // Скидка 3% при оплате в мае текущего года
        if ($isTravelDateInRange && $paymentMonth === 5 && $paymentYear === $startYear) {
            return 0.03;
        }

        if (4 <= $startMonth && 9 >= $startMonth && $startYear === $paymentYear + 1) {
            if ((12 > $paymentMonth && $paymentYear === $startYear - 1) || (11 === $paymentMonth && $paymentYear === $startYear - 1)) {
                return 0.07;
            }
            if (12 === $paymentMonth && $paymentYear === $startYear - 1) {
                return 0.05;
            }
        }
        if (1 === $paymentMonth && $paymentYear === $startYear) {
            return 0.03;
        }
        return 0;
    }
}
