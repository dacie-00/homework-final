<?php

namespace App\Console\Commands;

use App\Models\CryptoCurrency;
use App\Services\CryptoCurrencyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FetchCryptoCurrencyIcons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-crypto-currency-icons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches crypto currency icons from external API';

    /**
     * Execute the console command.
     */
    public function handle(CryptoCurrencyService $cryptoCurrencyService): int
    {
        try {
            $cryptoCurrencyService->fetchIcons(
                CryptoCurrency::all()->pluck('symbol')->toArray()
            );
        } catch (\Exception $e) {
            Log::error($e);
            return 1;
        }

        return 0;
    }
}
