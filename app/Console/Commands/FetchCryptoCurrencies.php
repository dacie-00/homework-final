<?php

namespace App\Console\Commands;

use App\Services\CryptoCurrencyService;
use Illuminate\Console\Command;

class FetchCryptoCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-crypto-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches crypto currencies from external API';

    /**
     * Execute the console command.
     */
    public function handle(CryptoCurrencyService $cryptoCurrencyService): void
    {
        $cryptoCurrencyService->getTop();
        echo 'Done!\n';
    }
}
