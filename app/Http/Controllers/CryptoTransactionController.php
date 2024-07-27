<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Models\CryptoPortfolioItem;
use App\Models\CryptoTransaction;
use App\Services\CryptoCurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CryptoTransactionController extends Controller
{
    public function store(Request $request, CryptoCurrencyService $cryptoCurrencyService): RedirectResponse
    {
        $validated = $request->validate([
            'account' => 'required',
            'type' => 'required|in:buy,sell',
            'currency' => 'required',
            'amount' => 'required|numeric|min:0.00000001|decimal:0,8',
        ]);

        /** @var ?Account $account */
        $account = Account::query()->where('iban', '=', $validated['account'])->first();

        if ($account === null ||
            $account->type !== 'investment' ||
            $account->user->name !== Auth::user()->name
        ) {
            throw ValidationException::withMessages([
                'account' => 'Invalid sender account.',
            ]);
        }

        if ($validated['type'] === 'sell') {
            $ownedCurrency = $account->cryptoPortfolioItems()->where('currency', $validated['currency'])->get();
            if ($ownedCurrency->isEmpty() || $ownedCurrency->first()->amount < $validated['amount']) {
                throw ValidationException::withMessages([
                    'account' => "You don't have enough of this currency to sell.",
                ]);
            }
        }

        $currencies = $cryptoCurrencyService->search([$validated['currency']]);
        if ($currencies->isEmpty()) {
            throw ValidationException::withMessages([
                'currency' => 'Currency not found.',
            ]);
        }

        /** @var CryptoCurrency $currency */
        $currency = $currencies->first();

        $price = $currency->price() * $validated['amount'];

        if ($validated['type'] === 'buy') {
            if ($price > $account->amount) {
                throw ValidationException::withMessages([
                    'amount' => "Your account doesn't have enough money.",
                ]);
            }
        }

        DB::transaction(function () use ($validated, $currency, $price, $account) {
            $cryptoItem = CryptoPortfolioItem::query()->firstOrCreate([
                'account_id' => $account->id,
                'currency' => $currency->symbol(),
            ]);


            if ($validated['type'] === 'buy') {
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
                'currency' => $currency->symbol(),
                'price' => $price,
            ]);
        });

        $verb = $validated['type'] === 'sell' ? 'sold' : 'bought';
        return redirect(route('crypto.index'))
            ->with('success', "Successfully $verb {$validated['amount']} {$currency->symbol()}!");
    }
}
