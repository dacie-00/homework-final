<?php
declare(strict_types=1);

namespace App\Models;

class CryptoCurrency
{
    private string $symbol;
    private float $price;

    public function __construct(string $symbol, float $price)
    {
        $this->symbol = $symbol;
        $this->price = $price;
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function price(): float
    {
        return $this->price;
    }
}
