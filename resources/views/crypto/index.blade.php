<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cryptocurrency index') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div>
                            {{ session('success') }}
                        </div>
                    @endif
                    <h2 class="font-bold px-6 py-4">Search</h2>
                    <form method="GET" action="{{ route('crypto.index') }}" id="search-form">
                        <x-input-label for="q" :value="__('Search for currency')"/>
                        <x-text-input id="q" name="q" value="{{ old('q') }}"></x-text-input>
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </form>

                    <h2 class="font-bold px-6 py-4">Buy currency</h2>
                    <form method="POST" action="{{ route('crypto-transaction.store') }}" id="transfer-form">
                        @csrf
                        <input hidden="hidden" id="type" name="type" value="buy">
                        <x-input-label for="account" :value="__('Account to make purchase from')"/>
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

                        <x-input-label for="currency" :value="__('Currency')"/>
                        <x-select id="currency" name="currency">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->symbol }}">
                                    {{
                                        $currency->symbol . ' (' .
                                        number_format($currency->price, 4) . ' USD)'
                                    }}
                                </option>
                            @endforeach
                        </x-select>
                        <x-input-error :messages="$errors->get('currency')" class="mt-2"/>

                        <x-input-label for="amount" :value="__('Amount')"/>
                        <x-text-input type="number" step="0.0001" id="amount" name="amount"
                                      value="{{ old('amount') }}"></x-text-input>
                        <x-input-error :messages="$errors->get('amount')" class="mt-2"/>
                        <x-input-error :messages="$errors->get('type')" class="mt-2"/>

                        <br>
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </form>

                    <h2 class="font-bold px-6 py-4">Currencies</h2>
                    @if ($currencies->isEmpty())
                        <p>No currencies found!</p>
                    @else
                        <x-table.table>
                            <x-table.head>
                                <x-table.row>
                                    <x-table.header>
                                        {{ __('Currency') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Price') }}
                                    </x-table.header>
                                </x-table.row>
                            </x-table.head>
                            <x-table.body>
                                @foreach($currencies as $currency)
                                    <x-table.row>
                                        <x-table.data>
                                            {{ $currency->symbol }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $currency->price . ' USD' }}
                                        </x-table.data>
                                    </x-table.row>
                                @endforeach
                            </x-table.body>
                            @endif
                        </x-table.table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
