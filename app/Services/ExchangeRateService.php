<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ExchangeRateService
{
    private string $url;

    public function __construct()
    {
        $this->url = 'https://www.bank.lv/vk/ecb.xml';
    }

    /**
     * @return Collection<Currency>
     */
    public function get(): Collection
    {
        $response = Http::get($this->url);

        // TODO: exception handling

        $currencies = Collect([new Currency('EUR', 1)]);

        $currenciesData = simplexml_load_string($response->body());
        foreach ($currenciesData->Currencies->Currency as $currencyData) {
            $currencies->add(new Currency((string)$currencyData->ID, (float)$currencyData->Rate));
        }

        return $currencies;
    }
}
