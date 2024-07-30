<x-app-layout title="Make a new Transfer - MockMiniBank">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Make a new money transfer
        </h2>
    </x-slot>

    <x-content>
        <h2 class="text-lg font-medium text-gray-900 mb-6">
            {{ __('Make a new money transfer') }}
        </h2>

        <form method="POST" action="{{ route('money-transfer.store') }}" id="transfer-form">
            @csrf
            <div class="space-y-6">
                <div>
                    <x-input-label for="account" :value="__('Account to make transfer from')"/>
                    <x-select id="account" name="account">
                        <option selected>Choose an account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->iban }}">
                                {{
                                    $account->name . ' (' .
                                    number_format($account->amount / 100, 2) . ' ' .
                                    $account->currency . ')'
                                }}
                            </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('account')" class="mt-2"/>
                </div>
                <div>
                    <x-input-label for="iban" :value="__('Receiver account IBAN')"/>
                    <x-text-input id="iban" name="iban" value="{{ old('iban') }}"></x-text-input>
                    <x-input-error :messages="$errors->get('iban')" class="mt-2"/>
                </div>
                <div>
                    <x-input-label for="name" :value="__('Receiver name')"/>
                    <x-text-input id="name" name="name" value="{{ old('name') }}"></x-text-input>
                    <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                </div>
                <div>
                    <x-input-label for="amount" :value="__('Amount')"/>
                    <x-text-input type="number" step="0.01" id="amount" name="amount"
                                  value="{{ old('amount') }}"></x-text-input>
                    <x-input-error :messages="$errors->get('amount')" class="mt-2"/>
                </div>
                <div>
                    <x-input-label for="note" :value="__('Note')"/>
                    <x-text-input id="note" name="note" value="{{ old('note') }}"></x-text-input>
                    <x-input-error :messages="$errors->get('note')" class="mt-2"/>
                </div>
                <div>
                    <x-primary-button>{{ __('Submit') }}</x-primary-button>
                </div>
            </div>
        </form>
    </x-content>
</x-app-layout>
