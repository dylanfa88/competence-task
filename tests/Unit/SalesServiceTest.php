<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\SalesService;
use PHPUnit\Framework\Attributes\DataProvider;

class SalesServiceTest extends TestCase
{
    public static function provider(): array
    {
        return [
            'data set 1' => [1, 10, 0.25, 23.33],
            'data set 2' => [2, 20.5, 0.25, 64.67],
            'data set 3' => [5, 12, 0.25, 90.0],
        ];
    }

    #[DataProvider('provider')]
    public function test_returns_valid_selling_price(
        $quantity,
        $unitCost,
        $profitMargin,
        $expected
    ): void
    {
        $salesService = new SalesService($quantity, $unitCost, $profitMargin);
        $salesService->calculateSellingPrice();
        $this->assertSame($expected, $salesService->getSellingPrice());
    }
}
