<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Models\Currency;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('account.index',
            [
                'user' => $user,
                'accounts' => $user->accounts,
            ]);
    }

    public function create(): View
    {
        return view('account.create', ['currencies' => Currency::CURRENCY_SYMBOLS]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:checking,investment',
            'currency' => ['required', new \App\Rules\Currency()],
        ]);

        Account::query()->create(
            [
                'name' => $validated['name'],
                'user_id' => Auth::id(),
                'iban' => fake()->iban(),
                'type' => $validated['type'],
                'currency' => $validated['type'] === 'checking' ? strtoupper($validated['currency']) : 'USD',
                'amount' => 100000,
            ]

        );

        return redirect(route('account.index'));
    }

    public function show(Account $account): View
    {
        $relations = ['accounts.user'];
        if ($account->type === 'investment') {
            $relations[] = 'accounts.cryptoTransactions';
            $relations[] = 'accounts.cryptoPortfolioItems';
        }
        $moneyTransfers = $account->moneyTransfers()
            ->with($relations)
            ->withPivot('type')
            ->get();

        $data = [
            'account' => $account,
            'moneyTransfers' => $moneyTransfers,
        ];

        if ($account->type === 'investment') {
            $data['cryptoCurrencies'] = CryptoCurrency::query()->get();
            $data['cryptoTransactions'] = $account->cryptoTransactions;
            $data['cryptoPortfolioItems'] = $account->cryptoPortfolioItems;
        }

        return view('account.show', $data);
    }
}
