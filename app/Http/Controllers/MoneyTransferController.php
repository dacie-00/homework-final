<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\MoneyTransfer;
use App\Models\User;
use App\Services\ExchangeRateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MoneyTransferController extends Controller
{
    public function create(): View
    {
        /** @var User $user */
        $user = Auth::user();
        return view(
            'money-transfer.create',
            [
                'accounts' => $user->accounts
            ]
        );
    }

    public function store(Request $request, ExchangeRateService $exchangeRateService)
    {
        $validated = $request->validate([
            'account' => 'required',
            'iban' => 'required',
            'name' => 'required',
            'amount' => 'required|numeric|min:0.01|decimal:0,2',
            'note' => 'max:200'
        ]);
        // convert from cents to full amount
        $validated['amount'] *= 100;
        // TODO: validate note and amount, and convert amount to cents
        // TODO: check if amount is less than or equal to account amount

        /** @var ?Account $senderAccount */
        $senderAccount = Account::query()->where('iban', '=', $validated['account'])->first();

        if ($senderAccount === null || $senderAccount->user->name !== Auth::user()->name) {
            throw ValidationException::withMessages([
                'account' => 'Invalid sender account.'
            ]);
        }

        if ($validated['amount'] > $senderAccount->amount) {
            throw ValidationException::withMessages([
                'amount' => "The account doesn't have this much money."
            ]);
        }

        /** @var ?Account $receiverAccount */
        $receiverAccount = Account::query()->where('iban', '=', $validated['iban'])->first();

        if ($receiverAccount === null || $receiverAccount->user->name !== $validated['name']) {
            throw ValidationException::withMessages([
                'iban' => 'No account with this IBAN and name.'
            ]);
        }

        if ($senderAccount->type === 'investment' && $receiverAccount->user->isNot($senderAccount->user)) {
            throw ValidationException::withMessages([
                'account' => 'Cannot make transactions from investment account to other users.'
            ]);
        }

        if ($receiverAccount->type === 'investment' && $receiverAccount->user->isNot($senderAccount->user)) {
            throw ValidationException::withMessages([
                'iban' => 'No account with this IBAN and name.'
            ]);
        }

        if ($receiverAccount->is($senderAccount)) {
            throw ValidationException::withMessages([
                'account' => 'Sending and receiving accounts cannot be the same account.'
            ]);
        }

        $exchangeRates = $exchangeRateService->get();
        // TODO: figure out a better way to find the rates
        $senderRate = $exchangeRates->first(fn($item) => $item->symbol() === $senderAccount->currency)->exchangeRate();
        $receiverRate = $exchangeRates->first(fn($item) => $item->symbol() === $receiverAccount->currency)->exchangeRate();

        $receiveAmount = $validated['amount'] * ($receiverRate / $senderRate);

        DB::transaction(function () use($validated, $senderAccount, $receiverAccount, $receiveAmount) {
            $senderAccount->amount -= $validated['amount'];
            $senderAccount->save();
            $receiverAccount->amount += $receiveAmount;
            $receiverAccount->save();

            $transfer = MoneyTransfer::query()->create([
                'amount_sent' => $validated['amount'],
                'currency_sent' => $senderAccount->currency,
                'amount_received' => $receiveAmount,
                'currency_received' => $receiverAccount->currency,
                'note' => $validated['note'],
            ]);
            $transfer->accounts()->attach(
                $senderAccount->id,
                ['type' => 'send']
            );
            $transfer->accounts()->attach(
                $receiverAccount->id,
                ['type' => 'receive']
            );
        });

        return redirect(route('account.index'))->with('success', 'The money transfer was successful.');
    }
}
