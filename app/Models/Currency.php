<?php
declare(strict_types=1);

namespace App\Models;

class Currency
{
    private float $exchangeRate;
    private string $symbol;
    public const CURRENCY_SYMBOLS = ['EUR', 'AUD', 'BGN', 'BRL', 'CAD', 'CHF', 'CNY', 'CZK', 'DKK', 'GBP', 'HKD', 'HUF', 'IDR', 'ILS', 'INR', 'ISK', 'JPY', 'KRW', 'MXN', 'MYR', 'NOK', 'NZD', 'PHP', 'PLN', 'RON', 'SEK', 'SGD', 'THB', 'TRY', 'USD', 'ZAR'];

    public function __construct(string $symbol, float $exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;
        $this->symbol = $symbol;
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
