<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return View("account.index",
            [
                "user" => $user,
                "checkingAccounts" => $user->checkingAccounts
            ]);
    }
}
