<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class StoreMoneyTransferRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sender-iban' => 'required',
            'receiver-iban' => 'required',
            'name' => 'required',
            'amount' => 'required|numeric|min:0.01|decimal:0,2',
            'note' => 'max:200',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $validated = $validator->validated();

                /** @var ?Account $senderAccount */
                $senderAccount = Account::query()->where('iban', '=', $validated['sender-iban'])->first();

                if ($senderAccount === null || $senderAccount->user->id !== Auth::user()->id) {
                    throw ValidationException::withMessages([
                        'sender-iban' => 'Invalid sender account.',
                    ]);
                }

                if ($validated['amount'] > $senderAccount->amount) {
                    throw ValidationException::withMessages([
                        'amount' => "The account doesn't have this much money.",
                    ]);
                }

                /** @var ?Account $receiverAccount */
                $receiverAccount = Account::query()->where('iban', '=', $validated['receiver-iban'])->first();

                if ($receiverAccount === null || $receiverAccount->user->name !== $validated['name']) {
                    throw ValidationException::withMessages([
                        'receiver-iban' => 'No account with this IBAN and name.',
                    ]);
                }

                if ($senderAccount->type === 'investment' && $receiverAccount->user->isNot($senderAccount->user)) {
                    throw ValidationException::withMessages([
                        'sender-iban' => 'Cannot make transactions from investment account to other users.',
                    ]);
                }

                if ($receiverAccount->type === 'investment' && $receiverAccount->user->isNot($senderAccount->user)) {
                    throw ValidationException::withMessages([
                        'receiver-iban' => 'No account with this IBAN and name.',
                    ]);
                }

                if ($receiverAccount->is($senderAccount)) {
                    throw ValidationException::withMessages([
                        'sender-iban' => 'Sending and receiving accounts cannot be the same account.',
                    ]);
                }
            }
        ];
    }
}
