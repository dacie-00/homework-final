<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\CryptoCurrency;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;
use Mockery\Exception;
use Nette\Utils\Json;

class CryptoCurrencyService
{
    private string $key;
    private string $baseUri = 'https://pro-api.coinmarketcap.com/v1/';

    public function __construct(string $key = null)
    {
        $this->key = $key ?? env('COIN_MARKET_CAP_API_KEY');
    }

    /**
     * @return Collection<CryptoCurrency>
     */
    public function getTop(int $page = 1, int $currenciesPerPage = 10): Collection
    {
        try {
            $response = $this->get(
                'cryptocurrency/listings/latest',
                [
                    'start' => 1 + $page * $currenciesPerPage - $currenciesPerPage,
                    'limit' => $currenciesPerPage,
                ]);
        } catch (ConnectionException|JsonException) {
            return collect();
        }

        $currencies = collect();
        foreach ($response->data as $currency) {
            $currencies->add(new CryptoCurrency(
                $currency->symbol,
                $currency->quote->USD->price
            ));
        }
        return $currencies;
    }

    /**
     * @param string[] $symbols
     * @return Collection<CryptoCurrency>
     */
    public function search(array $symbols): Collection
    {
        $symbols = array_map(fn($code) => strtoupper($code), $symbols);
        try {
            $response = $this->get(
                'cryptocurrency/quotes/latest',
                [
                    'symbol' => implode(',', $symbols),
                ]);
        } catch (ConnectionException|JsonException) {
            return collect();
        }

        if (!get_object_vars($response->data)) {
            return collect();
        }
        $currencies = collect();
        foreach ($symbols as $ticker) {
            if (!isset($response->data->$ticker) || !$response->data->$ticker->is_active) {
                continue;
            }
            $currency = $response->data->$ticker;
            $currencies->add(
                new CryptoCurrency(
                    $currency->symbol,
                    $currency->quote->USD->price
                )
            );
        }
        return $currencies;
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
