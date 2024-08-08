<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCryptoTransactionRequest;
use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Models\CryptoPortfolioItem;
use App\Models\CryptoTransaction;
use App\Services\CryptoCurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CryptoTransactionController extends Controller
{
    public function store(StoreCryptoTransactionRequest $request, CryptoCurrencyService $cryptoCurrencyService): RedirectResponse
    {
        $validated = $request->validated();

        /** @var ?Account $account */
        $account = Account::query()->where('iban', '=', $validated['account'])->first();

        $currencies = $cryptoCurrencyService->search([$validated['currency']]);
        /** @var CryptoCurrency $currency */
        $currency = $currencies->first();

        $price = $currency->price * $validated['amount'];

        DB::transaction(function () use ($validated, $currency, $price, $account) {
            $cryptoItem = CryptoPortfolioItem::query()->firstOrCreate([
                'account_id' => $account->id,
                'currency' => $currency->symbol,
            ]);


            if ($validated['type'] === 'buy') {

                // get ratio of the amount currency in this purchase relative to total in wallet
                $ratio = $validated['amount'] / ($cryptoItem->amount + $validated['amount']);
                $purchaseAverage = $price / $validated['amount'];
                // do linear interpolation between current average and new purchase average based on ratio
                $cryptoItem->average_price = $cryptoItem->average_price + $ratio * ($purchaseAverage - $cryptoItem->average_price);

                $account->amount -= $price;
                $cryptoItem->amount += $validated['amount'];
            } else {
                $account->amount += $price;
                $cryptoItem->amount -= $validated['amount'];
            }
            $account->save();
            $cryptoItem->save();

            CryptoTransaction::query()->create([
                'account_id' => $account->id,
                'type' => $validated['type'],
                'amount' => $validated['amount'],
                'currency' => $currency->symbol,
                'price' => $price,
            ]);
        });

        $verb = $validated['type'] === 'sell' ? 'sold' : 'bought';
        return redirect(route('crypto.index'))
            ->with('success', "Successfully $verb {$validated['amount']} $currency->symbol!");
    }
}
