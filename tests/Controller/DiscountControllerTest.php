<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use JsonException;
use PHPUnit\Framework\Attributes\CoversNothing;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[CoversNothing]
final class DiscountControllerTest extends WebTestCase
{

    /**
     * @throws JsonException
     */
    public function testCalculateDiscount(): void
    {
        $client = self::createClient();

        $client->request(Request::METHOD_POST, '/api/discount', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'startDate' => '2027-05-01',
            'paymentDate' => '2026-11-15',
            'birthDate' => '2015-04-01',
            'initialPrice' => 10000.0,
        ], JSON_THROW_ON_ERROR));

        $response = $client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $content = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $expectedDiscountedCost = 8370;
        $this->assertEquals($expectedDiscountedCost, $content['discountedCost']);
    }
}
