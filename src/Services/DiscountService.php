<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\DiscountDto;

interface DiscountService
{
    public function calculateDiscount(DiscountDto $discountDto): float;
}
