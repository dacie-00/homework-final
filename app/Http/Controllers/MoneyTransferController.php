<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMoneyTransferRequest;
use App\Models\Account;
use App\Models\MoneyTransfer;
use App\Models\User;
use App\Services\ExchangeRateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
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
                'accounts' => $user->accounts,
            ]
        );
    }

    public function store(StoreMoneyTransferRequest $request, ExchangeRateService $exchangeRateService)
    {
        $validated = $request->validated();

        $senderAccount = Account::query()->where('iban', '=', $validated['sender-iban'])->first();
        $receiverAccount = Account::query()->where('iban', '=', $validated['receiver-iban'])->first();

        $exchangeRates = $exchangeRateService->get();
        // TODO: figure out a better way to find the rates
        $senderRate = $exchangeRates->first(fn($item) => $item->symbol() === $senderAccount->currency)->exchangeRate();
        $receiverRate = $exchangeRates->first(fn($item) => $item->symbol() === $receiverAccount->currency)->exchangeRate();
        $receiveAmount = $validated['amount'] * ($receiverRate / $senderRate);

        DB::transaction(function () use ($validated, $senderAccount, $receiverAccount, $receiveAmount) {
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
