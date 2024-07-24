<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create a new bank account
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('account.store') }}">
                        @csrf
                        <x-input-label for="name" :value="__('Name')"/>
                        <x-text-input id="name" name="name" value="{{ old('name') }}"></x-text-input>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />

                        <x-input-label for="type" :value="__('Account type')"/>
                        <x-select id="type" name="type">
                            <option value="checking" selected>Checking</option>
                            <option value="investment" selected>Investment</option>
                        </x-select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />

{{--                    TODO: disable currency selection when investment type is selected (probably use js)--}}
                        <x-input-label for="currency" :value="__('Currency')"/>
                        <x-select id="currency" name="currency">
                            <option selected>Select a currency</option>
                            @foreach($currencies as $currency)
                                <option value="{{ $currency }}">
                                    {{ $currency }}
                                </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        <br>
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
