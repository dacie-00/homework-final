<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\CryptoCurrency;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;

class CryptoCurrencyService
{
    private string $key;
    private string $baseUri = 'https://pro-api.coinmarketcap.com/v1/';

    public function __construct(string $key = null)
    {
        $this->key = $key ?? env('COIN_MARKET_CAP_API_KEY');
    }

    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    public function getTop(int $page = 1, int $currenciesPerPage = 50): void
    {
        $response = $this->get(
            'cryptocurrency/listings/latest',
            [
                'start' => 1 + $page * $currenciesPerPage - $currenciesPerPage,
                'limit' => $currenciesPerPage,
            ]);

        foreach ($response->data as $rank => $currency) {
            CryptoCurrency::query()->updateOrCreate(
                ['rank' => $rank + 1],
                ['symbol' => $currency->symbol, 'price' => $currency->quote->USD->price]
            );
        }
    }

    /**
     * @param string[] $symbols
     * @return Collection<CryptoCurrency>
     */
    public function search(array $symbols): Collection
    {
        $symbols = array_map(fn($code) => strtoupper($code), $symbols);
        $newSymbols = [];
        foreach($symbols as &$symbol) {
            $currency = CryptoCurrency::query()->where('symbol', $symbol)->first();
            if ($currency !== null) {
                $symbol = $currency;
            } else {
                $newSymbols[] = $symbol;
            }
        }
        unset($symbol);

        if (empty($newSymbols)) {
            return collect($symbols);
        }

        try {
            $response = $this->get(
                'cryptocurrency/quotes/latest',
                [
                    'symbol' => implode(',', $newSymbols),
                ]);
        } catch (ConnectionException|JsonException) {
            return collect();
        }

        if (!get_object_vars($response->data)) {
            return collect();
        }

        foreacH($symbols as $i => &$symbol) {
            if (is_string($symbol) === false) {
                continue;
            }
            if (!isset($response->data->$symbol) || !$response->data->$symbol->is_active) {
                unset($symbols[$i]);
                continue;
            }
            $currency = $response->data->$symbol;
            $symbol = CryptoCurrency::query()->updateOrCreate(
                ['symbol' => $currency->symbol],
                [
                    'price' => $currency->quote->USD->price
                ]);
        }
        unset($symbol);

        $symbols = array_values($symbols);
        return collect($symbols);
    }

    /**
     * @throws ConnectionException
     * @throws JsonException
     */
    private function get(string $url, array $query): \stdClass
    {
        try {
            $response = Http::withHeaders(
                [
                    'Accepts' => 'application/json',
                    'X-CMC_PRO_API_KEY' => $this->key,
                ]
            )->get($this->baseUri . $url, $query);
        } catch (ConnectionException $e) {
            Log::error($e);
            throw $e;
        }

        try {
            return json_decode(
                $response->body(),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            Log::error($e);
            throw $e;
        }
    }
}
