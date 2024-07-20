<?php

namespace App\Http\Controllers;

use App\Models\CheckingAccount;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return View("accounts.index",
            [
                "user" => $user,
                "checkingAccounts" => $user->checkingAccounts
            ]);
    }

    public function show(CheckingAccount $checkingAccount): View
    {
        return View("accounts.show",
            [
                "checkingAccount" => $checkingAccount,
            ]);
    }
}
