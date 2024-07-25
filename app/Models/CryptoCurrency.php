<?php
declare(strict_types=1);

namespace App\Models;

class CryptoCurrency
{
    private string $symbol;
    private float $exchangeRate;

    public function __construct(string $symbol, float $exchangeRate)
    {
        $this->symbol = $symbol;
        $this->exchangeRate = $exchangeRate;
    }

    public function symbol(): string
    {
        return $this->symbol;
    }

    public function exchangeRate(): float
    {
        return $this->exchangeRate;
    }
}
