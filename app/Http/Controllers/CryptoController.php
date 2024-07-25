<?php

namespace App\Http\Controllers;

use App\Services\CryptoCurrencyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
        return view('crypto.index', ['currencies' => $currencies]);
    }

    public function show(string $symbol, CryptoCurrencyService $cryptoCurrencyService): View
    {
        return view('crypto.show', ['currencies' => $cryptoCurrencyService->search($symbol)]);
    }
}
