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

        return view("accounts.index",
            [
                "user" => $user,
                "checkingAccounts" => $user->checkingAccounts
            ]);
    }

    public function show(CheckingAccount $checkingAccount): View
    {
        return view("accounts.show",
            [
                "checkingAccount" => $checkingAccount,
            ]);
    }

    public function create(): View
    {
        return view("accounts.create");
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
                "currency" => strtoupper($validated["currency"]),
                "amount" => 1000,
            ]

        );

        return redirect(route("accounts.index"));
    }
}
