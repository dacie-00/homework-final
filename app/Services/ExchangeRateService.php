<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    private string $url;

    public function __construct()
    {
        $this->url = 'https://www.bank.lv/vk/ecb.xml';
    }

    public function get(): void
    {
        $response = Http::get($this->url);

        $currenciesData = simplexml_load_string($response->body());

        foreach ($currenciesData->Currencies->Currency as $currencyData) {
            Currency::query()->updateOrCreate([
                'symbol' => (string)$currencyData->ID,
                'exchange_rate' => (float)$currencyData->Rate * 100,
            ]);
        }
        Currency::query()->updateOrCreate([
            'symbol' => 'EUR',
            'exchange_rate' => 100,
        ]);
    }
}
