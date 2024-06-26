<?php

declare(strict_types=1);

namespace App\Tests\Services;

use DateTimeImmutable;
use App\Dto\DiscountDto;
use PHPUnit\Framework\TestCase;
use App\Services\EarlyBookingDiscountStrategy;
use Tizix\DataTransferObject\Exceptions\UnknownProperties;
use Tizix\DataTransferObject\Exceptions\ValidationException;

/**
 * @internal
 *
 * @coversNothing
 */
final class EarlyBookingDiscountStrategyTest extends TestCase
{
    protected int $initialPrice = 10000;
    private EarlyBookingDiscountStrategy $earlyBookingDiscountStrategy;

    protected function setUp(): void
    {
        $this->earlyBookingDiscountStrategy = new EarlyBookingDiscountStrategy();
    }

    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function testCalculateDiscountSevenPercent(): void
    {
        $startDate = new DateTimeImmutable('2027-05-01');
        $birthDate = new DateTimeImmutable('2000-01-01');
        $paymentDate = new DateTimeImmutable('2026-10-30');
        $discountDto = new DiscountDto(
            startDate: $startDate,
            paymentDate: $paymentDate,
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );
        $discountedPrice = $this->earlyBookingDiscountStrategy->calculateDiscount($discountDto);

        $this->assertEquals(9300, $discountedPrice);
        $startDate = new DateTimeImmutable('2027-08-01');
        $birthDate = new DateTimeImmutable('2000-01-01');
        $paymentDate = new DateTimeImmutable('2026-12-15');
        $discountDto = new DiscountDto(
            startDate: $startDate,
            paymentDate: $paymentDate,
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );
        $discountedPrice = $this->earlyBookingDiscountStrategy->calculateDiscount($discountDto);

        $this->assertEquals(9500, $discountedPrice);

        $startDate = new DateTimeImmutable('2028-08-01');
        $birthDate = new DateTimeImmutable('2000-01-01');
        $paymentDate = new DateTimeImmutable('2028-01-15');
        $discountDto = new DiscountDto(
            startDate: $startDate,
            paymentDate: $paymentDate,
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );
        $discountedPrice = $this->earlyBookingDiscountStrategy->calculateDiscount($discountDto);

        $this->assertEquals(9700, $discountedPrice);
    }



    /**
     * @throws UnknownProperties
     * @throws ValidationException
     */
    public function testCalculateDiscountForTravelDatesFromOctoberToJanuary(): void
    {
        $startDate = new DateTimeImmutable('2027-10-01');
        $birthDate = new DateTimeImmutable('2000-01-01');
        $paymentDate = new DateTimeImmutable('2027-03-15');
        $discountDto = new DiscountDto(
            startDate: $startDate,
            paymentDate: $paymentDate,
            birthDate: $birthDate,
            initialPrice: $this->initialPrice
        );
        $discountedPrice = $this->earlyBookingDiscountStrategy->calculateDiscount($discountDto);
        $this->assertEquals(9300, $discountedPrice);

        $paymentDate = new DateTimeImmutable('2027-04-15');
        $discountDto->paymentDate = $paymentDate;
        $discountedPrice = $this->earlyBookingDiscountStrategy->calculateDiscount($discountDto);
        $this->assertEquals(9500, $discountedPrice);

        $paymentDate = new DateTimeImmutable('2027-05-15');
        $discountDto->paymentDate = $paymentDate;
        $discountedPrice = $this->earlyBookingDiscountStrategy->calculateDiscount($discountDto);
        $this->assertEquals(9700, $discountedPrice);
    }

}
