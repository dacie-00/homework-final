<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Make a new money transfer
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('money-transfer.store') }}" id="transfer-form">
                        @csrf
                        <x-input-label for="account" :value="__('Account to make transfer from')"/>
                        <x-select id="account" name="account">
                            <option selected>Choose an account</option>
                            @foreach($checkingAccounts as $checkingAccount)
                                <option value="{{ $checkingAccount->iban }}">
                                    {{
                                        $checkingAccount->name . ' (' .
                                        number_format($checkingAccount->amount, 2) . ' ' .
                                        $checkingAccount->currency . ')'
                                    }}
                                </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('account')" class="mt-2"/>
                        <x-input-label for="iban" :value="__('Receiver account IBAN')"/>
                        <x-text-input id="iban" name="iban" value="{{ old('iban') }}"></x-text-input>
                        <x-input-error :messages="$errors->get('iban')" class="mt-2"/>

                        <x-input-label for="name" :value="__('Receiver name')"/>
                        <x-text-input id="name" name="name" value="{{ old('name') }}"></x-text-input>
                        <x-input-error :messages="$errors->get('name')" class="mt-2"/>

                        <x-input-label for="amount" :value="__('Amount')"/>
                        <x-text-input type="number" step="0.01" id="amount" name="amount"
                                      value="{{ old('amount') }}"></x-text-input>
                        <x-input-error :messages="$errors->get('amount')" class="mt-2"/>

                        <x-input-label for="note" :value="__('Note')"/>
                        <x-text-input id="note" name="note" value="{{ old('note') }}"></x-text-input>
                        <x-input-error :messages="$errors->get('note')" class="mt-2"/>
                        <br>
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
