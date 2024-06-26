<?php

declare(strict_types=1);

namespace App\Dto;

use DateTimeImmutable;
use Tizix\DataTransferObject\DataTransferObject;

final class DiscountDto extends DataTransferObject
{
    public DateTimeImmutable $startDate;

    public ?DateTimeImmutable $paymentDate;
    public DateTimeImmutable $birthDate;
    public float $initialPrice;
}
