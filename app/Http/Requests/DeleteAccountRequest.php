<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class DeleteAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::id() === $this->route('account')->user_id;
    }

    public function rules(): array
    {
        return [];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $account = $this->route('account');

                if ($account->amount > 0) {
                    throw ValidationException::withMessages([
                        'account' => 'Cannot delete account with funds in it.',
                    ]);
                }

                if ($account->user_id !== Auth::id()) {
                    abort(403);
                }
            },
        ];
    }
}
