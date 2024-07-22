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
                        <x-text-input id="name" name="name"></x-text-input>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />

                        <x-input-label for="currency" :value="__('Currency')"/>
                        <x-text-input id="currency" name="currency"></x-text-input>
                        <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        <br>
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
