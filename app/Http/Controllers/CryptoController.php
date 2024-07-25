<?php

namespace App\Http\Controllers;

use App\Services\CryptoCurrencyService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CryptoController extends Controller
{
    public function index(CryptoCurrencyService $cryptoCurrencyService): View
    {
        return view('crypto.index', ['currencies' => $cryptoCurrencyService->getTop()]);
    }
}
