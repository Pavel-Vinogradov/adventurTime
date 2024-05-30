<?php

declare(strict_types=1);

namespace App\Request;

use App\Core\Requests\BaseRequest;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

final class DiscountRequest extends BaseRequest
{
    #[Date]
    #[Type('string')]
    public readonly ?string $startDate;

    #[Date]
    #[Type('string')]
    public readonly ?string $paymentDate;

    #[Date]
    #[NotBlank]
    #[Type('string')]
    public readonly string $birthDate;

    #[NotBlank]
    #[Type('float')]
    #[Positive]
    public readonly float $initialPrice;
}
