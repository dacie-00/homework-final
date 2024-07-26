<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\CryptoCurrencyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CryptoController extends Controller
{
    public function index(Request $request, CryptoCurrencyService $cryptoCurrencyService): View
    {
        if ($request->query('q') !== null) {
            $currencies = $cryptoCurrencyService->search(
                explode(',', $request->query('q'))
            );
        } else {
            $currencies = $cryptoCurrencyService->getTop();
        }
        $accounts = Account::query()
            ->where('user_id', Auth::id())
            ->where('type', 'investment')
            ->get();
        return view('crypto.index', ['currencies' => $currencies, 'accounts' => $accounts]);
    }

    public function show(string $symbol, CryptoCurrencyService $cryptoCurrencyService): View
    {
        return view('crypto.show', ['currencies' => $cryptoCurrencyService->search($symbol)]);
    }
}
