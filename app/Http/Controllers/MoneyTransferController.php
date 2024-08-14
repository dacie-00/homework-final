<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMoneyTransferRequest;
use App\Models\Account;
use App\Models\Currency;
use App\Models\MoneyTransfer;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function store(StoreMoneyTransferRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $senderAccount = Account::query()->where('iban', '=', $validated['sender-iban'])->first();
        $receiverAccount = Account::query()->where('iban', '=', $validated['receiver-iban'])->first();

        $senderRate = Currency::query()->where('symbol', $senderAccount->currency)->first()->exchange_rate;
        $receiverRate = Currency::query()->where('symbol', $receiverAccount->currency)->first()->exchange_rate;
        $receiveAmount = $validated['amount'] * ($receiverRate / $senderRate);

        DB::transaction(function () use ($validated, $senderAccount, $receiverAccount, $receiveAmount) {
            $senderAccount->amount -= $validated['amount'] * 100;
            $senderAccount->save();
            $receiverAccount->amount += $receiveAmount * 100;
            $receiverAccount->save();

            $transfer = MoneyTransfer::query()->create([
                'amount_sent' => $validated['amount'] * 100,
                'currency_sent' => $senderAccount->currency,
                'amount_received' => $receiveAmount * 100,
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
