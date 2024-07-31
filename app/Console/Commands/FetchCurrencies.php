<?php

namespace App\Console\Commands;

use App\Services\ExchangeRateService;
use Exception;
use Illuminate\Console\Command;

class FetchCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches currencies from Latvijas Banka API';

    /**
     * Execute the console command.
     */
    public function handle(ExchangeRateService $exchangeRateService): int
    {
        try {
            $exchangeRateService->get();
        } catch (Exception $e) {
            echo $e->getMessage();
            return 1;
        }
        return 0;
    }
}
