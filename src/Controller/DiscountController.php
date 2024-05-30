<?php

declare(strict_types=1);

namespace App\Controller;

use Exception;
use DateTimeImmutable;
use App\Dto\DiscountDto;
use App\Request\DiscountRequest;
use App\Services\DiscountService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/discount')]
final class DiscountController extends AbstractController
{
    public function __construct(private readonly DiscountService $discountService) {}

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     * @throws Exception
     */
    #[Route(name: 'discount', methods: ['POST'])]
    public function store(DiscountRequest $discountRequest): Response
    {
        $discountDto = new DiscountDto(
            startDate: $discountRequest->startDate ? new DateTimeImmutable($discountRequest->startDate) : null,
            paymentDate: $discountRequest->paymentDate ? new DateTimeImmutable($discountRequest->paymentDate) : null,
            birthDate: new DateTimeImmutable($discountRequest->birthDate),
            initialPrice: $discountRequest->initialPrice
        );
        $discountedCost = $this->discountService->calculateDiscount($discountDto);

        return $this->json(['result' => $discountedCost]);
    }
}
