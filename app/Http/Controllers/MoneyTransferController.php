<?php

namespace App\Http\Controllers;

use App\Models\CheckingAccount;
use App\Models\MoneyTransfer;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                "checkingAccounts" => $user->checkingAccounts
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            "account" => "required",
            "iban" => "required",
            "name" => "required",
            "amount" => "required|numeric|min:0.01|decimal:0,2",
        ]);
        // TODO: validate note and amount, and convert amount to cents

        $senderAccount = CheckingAccount::query()->where("iban", "=", $request["account"])->first();

        if ($senderAccount === null || $senderAccount->user->name !== Auth::user()->name) {
            throw ValidationException::withMessages([
                "account" => "Invalid sender account."
            ]);
        }

        $receiverAccount = CheckingAccount::query()->where("iban", "=", $request["iban"])->first();

        if ($receiverAccount === null || $receiverAccount->user->name !== $request["name"]) {
            throw ValidationException::withMessages([
                "iban" => "No account with this IBAN and name."
            ]);
        }

        if ($receiverAccount->is($senderAccount)) {
            throw ValidationException::withMessages([
                "account" => "Sending and receiving accounts cannot be the same account."
            ]);
        }


        // TODO: do currency conversion with bank API
        // TODO: add note to money transfers
        $transfer = MoneyTransfer::query()->create([
            "amount_sent" => $request["amount"],
            "currency_sent" => $senderAccount->currency,
            "amount_received" => $request["amount"],
            "currency_received" => $receiverAccount->currency,
        ]);
        $transfer->checkingAccounts()->attach(
            $senderAccount->id,
            ["type" => "send"]
        );
        $transfer->checkingAccounts()->attach(
            $receiverAccount->id,
            ["type" => "receive"]
        );

        throw ValidationException::withMessages([
            "iban" => "Success!!!!"
        ]);
    }
}
