<?php

namespace App\Http\Controllers;

use App\Models\CheckingAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view("account.index",
            [
                "user" => $user,
                "checkingAccounts" => $user->checkingAccounts
            ]);
    }

    public function create(): View
    {
        return view("account.create");
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "currency" => "required|string|size:3",
        ]);

        CheckingAccount::query()->create(
            [
                "name" => $validated["name"],
                "user_id" => Auth::id(),
                "iban" => fake()->iban(),
                "currency" => strtoupper($validated["currency"]),
                "amount" => 100000,
            ]

        );

        return redirect(route("account.index"));
    }

    public function show(CheckingAccount $checkingAccount): View
    {
        $moneyTransfers = $checkingAccount->moneyTransfer()
            ->with('checkingAccounts.user')
            ->withPivot('type')
            ->get();
        return view("account.show",
            [
                "checkingAccount" => $checkingAccount,
                "moneyTransfers" => $moneyTransfers,
            ]);
    }
}
