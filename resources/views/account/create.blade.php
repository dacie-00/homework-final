<x-app-layout title="Create a new Account - MockMiniBank">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a new bank account
        </h2>
    </x-slot>

    <x-section>
        <x-section-heading class="-mb-4">
            {{ __('Create a new bank account') }}
        </x-section-heading>

        <p class="mt-1 mb-6 text-sm text-gray-600">
            {{ __('Note: that investment accounts can only have USD as the account currency.') }}
        </p>
        <form x-data="{type: 'checking', value: ''}" method="POST" action="{{ route('account.store') }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <x-input-label for="name" :value="__('Name')"/>
                    <x-text-input id="name" name="name" value="{{ old('name') }}"></x-text-input>
                    <x-input-error :messages="$errors->get('name')" class="mt-2"/>
                </div>

                <div>
                    <x-input-label for="type" :value="__('Account type')"/>
                    <x-select x-model='type' id="type" name="type">
                        <option value="checking" selected>Checking</option>
                        <option value="investment" selected>Investment</option>
                    </x-select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2"/>
                </div>

                <div>
                    <x-input-label for="currency" :value="__('Currency')"/>
                    <x-select id="currency" name="currency">
                        <option selected>Select a currency</option>
                        @foreach($currencies as $currency)
                            <option :disabled="type === 'investment' && $el.value !== 'USD'"
                                    :selected="type === 'investment' && $el.value === 'USD'" value="{{ $currency }}">
                                {{ $currency }}
                            </option>
                        @endforeach
                    </x-select>
                    <x-input-error :messages="$errors->get('currency')" class="mt-2"/>
                </div>
                <x-primary-button>{{ __('Submit') }}</x-primary-button>
            </div>
        </form>
    </x-section>
</x-app-layout>
