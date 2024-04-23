<?php

declare(strict_types = 1);

namespace App\Services;

class SalesService
{
    protected float $sellingPrice;
    protected float $profitMargin = 0.25;
    protected float $shippingCost = 10;

    public function __construct(
        protected float $quantity,
        protected float $unitCost,
    ) {
    }

    public function calculateSellingPrice(): void
    {
        $cost = $this->getCost();
        $this->sellingPrice = ($cost/(1-$this->profitMargin)+$this->shippingCost);
    }

    public function getSellingPrice(): float
    {
        return round($this->sellingPrice, 2);
    }

    protected function getCost():float
    {
        return $this->quantity*$this->unitCost;
    }
}
