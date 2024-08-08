<?php

namespace App\Http\Requests;

use App\Models\Account;
use App\Models\CryptoCurrency;
use App\Services\CryptoCurrencyService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class StoreCryptoTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::guest() === false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'account' => 'required',
            'type' => 'required|in:buy,sell',
            'currency' => 'required',
            'amount' => 'required|numeric|min:0.00000001|decimal:0,8',
        ];
    }

    public function after(CryptoCurrencyService $cryptoCurrencyService): array
    {
        return [
            function (Validator $validator) use ($cryptoCurrencyService) {
                $validated = $validator->validated();
                /** @var ?Account $account */
                $account = Account::query()->where('iban', '=', $validated['account'])->first();

                if ($account === null ||
                    $account->type !== 'investment' ||
                    $account->user->name !== Auth::user()->name
                ) {
                    throw ValidationException::withMessages([
                        'account' => 'Invalid sender account.',
                    ]);
                }

                if ($validated['type'] === 'sell') {
                    $ownedCurrency = $account->cryptoPortfolioItems()->where('currency', $validated['currency'])->get();
                    if ($ownedCurrency->isEmpty() || $ownedCurrency->first()->amount < $validated['amount']) {
                        throw ValidationException::withMessages([
                            'account' => "You don't have enough of this currency to sell.",
                        ]);
                    }
                }

                $currencies = $cryptoCurrencyService->search([$validated['currency']]);
                if ($currencies->isEmpty()) {
                    throw ValidationException::withMessages([
                        'currency' => 'Currency not found.',
                    ]);
                }

                /** @var CryptoCurrency $currency */
                $currency = $currencies->first();

                $price = $currency->price * $validated['amount'];

                if ($validated['type'] === 'buy') {
                    if ($price > $account->amount) {
                        throw ValidationException::withMessages([
                            'amount' => "Your account doesn't have enough money.",
                        ]);
                    }
                }
            },
        ];
    }
}
