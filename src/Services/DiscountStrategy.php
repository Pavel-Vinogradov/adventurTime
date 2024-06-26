<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\DiscountDto;

interface DiscountStrategy
{
    public function calculateDiscount(DiscountDto $discountDto): float;
}
