<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Models\Currency;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request): View
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
                'currency' => $validated['type'] === Account::TYPE_CHECKING ? strtoupper($validated['currency']) : 'USD',
                'amount' => 100000,
            ]

        );

        return redirect(route('account.index'));
    }

    public function show(Account $account): View
    {
        $relations = ['accounts.user'];

        $moneyTransfers = $account->moneyTransfers()
            ->with($relations)
            ->withPivot('type')
            ->paginate(20)->withQueryString();

        $data = [
            'account' => $account,
            'moneyTransfers' => $moneyTransfers,
        ];

        if ($account->type === Account::TYPE_INVESTMENT) {
            $data['cryptoTransactions'] = $account->cryptoTransactions->paginate(10)->withQueryString();
            $data['cryptoPortfolioItems'] = $account->cryptoPortfolioItems->paginate(10)->withQueryString();
        }

        return view('account.show', $data);
    }

    public function delete(DeleteAccountRequest $request, Account $account): RedirectResponse
    {
        $account->delete();

        $request->session()->flash('success', 'Account successfully deleted.');
        return redirect(route('account.index'));
    }
}
