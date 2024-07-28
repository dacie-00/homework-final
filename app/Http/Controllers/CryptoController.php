<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CryptoCurrency;
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
            $currencies = CryptoCurrency::query()
                ->where('rank', '<=', 25)
                ->orderBy('rank')
                ->get();
        }
        $accounts = Account::query()
            ->where('user_id', Auth::id())
            ->where('type', 'investment')
            ->get();

        return view(
            'crypto.index',
            ['currencies' => $currencies, 'accounts' => $accounts]
        );
    }
}
