<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\CryptoCurrency;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

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
        $url = 'cryptocurrency/listings/latest';
        $parameters = [
            'start' => 1 + $page * $currenciesPerPage - $currenciesPerPage,
            'limit' => $currenciesPerPage,
        ];

        $queryString = http_build_query($parameters);

        try {
            $response = Http::withHeaders(
                [
                    'Accepts' => 'application/json',
                    'X-CMC_PRO_API_KEY' => $this->key,
                ]
            )->get("{$this->baseUri}$url?$queryString");
        } catch (ConnectionException $e) {
            $response = $e->getResponse();
            $responseBody = json_decode(
                $response->getBody()->getContents(),
                false,
                512,
                JSON_THROW_ON_ERROR
            );
            // TODO: error handling here
            dd("oops", $responseBody);
        }

        $currencyResponse = json_decode(
            $response->getBody()->getContents(),
            false,
            512,
            JSON_THROW_ON_ERROR
        );

        $currencies = collect();
        foreach ($currencyResponse->data as $currency) {
            $currencies->add(new CryptoCurrency(
                $currency->symbol,
                $currency->quote->USD->price
            ));
        }
        return $currencies;
    }
}
