<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\DiscountDto;

final readonly class CombinedDiscountStrategy implements DiscountStrategy
{
    public function __construct(private ChildDiscountStrategy $childDiscountStrategy, private EarlyBookingDiscountStrategy $earlyBookingDiscountStrategy)
    {
    }

    public function calculateDiscount(DiscountDto $discountDto): float
    {
        $discountedPrice = $this->childDiscountStrategy->calculateDiscount($discountDto);
        $discountDto->initialPrice = $discountedPrice;
        return $this->earlyBookingDiscountStrategy->calculateDiscount($discountDto);
    }
}
