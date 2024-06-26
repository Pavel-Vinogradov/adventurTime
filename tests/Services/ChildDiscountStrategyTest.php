<?php

declare(strict_types=1);

namespace App\Tests\Services;

use DateTimeImmutable;
use App\Dto\DiscountDto;
use Random\RandomException;
use PHPUnit\Framework\TestCase;
use App\Services\ChildDiscountStrategy;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

/**
 * @internal
 *
 * @coversNothing
 */
final class ChildDiscountStrategyTest extends TestCase
{
    protected int $initialPrice = 1000;
    private ChildDiscountStrategy $discountService;

    protected function setUp(): void
    {
        $this->discountService = new ChildDiscountStrategy();
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     * @throws RandomException
     */
    public function testCalculateDiscountForChildUnder3(): void
    {
        $birthDate = new DateTimeImmutable('2021-05-30');
        $travelStartDate = new DateTimeImmutable('2024-05-30');

        $discountDto = new DiscountDto(
            startDate: $travelStartDate,
            paymentDate: $this->getRandomPaymentDate(),
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );
        $discountedPrice = $this->discountService->calculateDiscount($discountDto);

        $this->assertEquals(200, $discountedPrice);
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     * @throws RandomException
     */
    public function testCalculateDiscountForChild3To5(): void
    {
        $birthDate = new DateTimeImmutable('2019-05-30');
        $travelStartDate = new DateTimeImmutable('2024-05-30');
        $discountDto = new DiscountDto(
            startDate: $travelStartDate,
            paymentDate: $this->getRandomPaymentDate(),
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );

        $discountedPrice = $this->discountService->calculateDiscount($discountDto);

        $this->assertEquals(200, $discountedPrice);
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     * @throws RandomException
     */
    public function testCalculateDiscountForChild6To11(): void
    {
        $birthDate = new DateTimeImmutable('2013-05-30');
        $travelStartDate = new DateTimeImmutable('2024-05-30');
        $discountDto = new DiscountDto(
            startDate: $travelStartDate,
            paymentDate: $this->getRandomPaymentDate(),
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );
        $discountedPrice = $this->discountService->calculateDiscount($discountDto);

        $expectedPrice = max($this->initialPrice - 4500, $this->initialPrice * 0.7);
        $this->assertEquals($expectedPrice, $discountedPrice);
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     * @throws RandomException
     */
    public function testCalculateDiscountForChild12To15(): void
    {
        $birthDate = new DateTimeImmutable('2010-05-30');
        $travelStartDate = new DateTimeImmutable('2024-05-30');
        $discountDto = new DiscountDto(
            startDate: $travelStartDate,
            paymentDate: $this->getRandomPaymentDate(),
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );
        $discountedPrice = $this->discountService->calculateDiscount($discountDto);

        $this->assertEquals(900.0, $discountedPrice);
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     * @throws RandomException
     */
    public function testCalculateDiscountForAdult(): void
    {
        $birthDate = new DateTimeImmutable('2000-05-30');
        $travelStartDate = new DateTimeImmutable('2024-05-30');
        $discountDto = new DiscountDto(
            startDate: $travelStartDate,
            paymentDate: $this->getRandomPaymentDate(),
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );
        $discountedPrice = $this->discountService->calculateDiscount($discountDto);

        $this->assertEquals($this->initialPrice, $discountedPrice);
    }

    /**
     * @throws RandomException
     */
    protected function getRandomPaymentDate(): ?DateTimeImmutable
    {
        $today = new DateTimeImmutable();
        $sixMonthsLater = $today->modify('+6 months');

        return 1 === random_int(0, 1) ? $today : $sixMonthsLater;
    }
}
