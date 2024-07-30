<x-app-layout title="Cryptocurrency Index - MockMiniBank">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cryptocurrency index') }}
        </h2>
    </x-slot>

    @if(session('success'))
        <h2 class="text-lg font-medium text-gray-900">{{ session('success') }}</h2>
    @endif

    <x-section class="pt-8">
        <x-section-heading>
            {{ __('Buy Currency') }}
        </x-section-heading>
        <form method="POST" action="{{ route('crypto-transaction.store') }}" id="transfer-form">
            @csrf
            <div class="space-y-6">
                <input hidden="hidden" id="type" name="type" value="buy">
                <div>
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
                </div>
                <div>
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
                </div>
                <div>
                    <x-input-label for="amount" :value="__('Amount')"/>
                    <x-text-input type="number" step="0.0001" id="amount" name="amount"
                                  value="{{ old('amount') }}"></x-text-input>
                    <x-input-error :messages="$errors->get('amount')" class="mt-2"/>
                    <x-input-error :messages="$errors->get('type')" class="mt-2"/>
                </div>
                <div>
                    <x-primary-button>{{ __('Submit') }}</x-primary-button>
                </div>
            </div>
        </form>
    </x-section>

    <x-section>
        <x-section-heading>
            {{ __('Search') }}
        </x-section-heading>
        <form method="GET" action="{{ route('crypto.index') }}" id="search-form">
            <div class="space-y-6">
                <div>
                    <x-input-label for="q" :value="__('Search for currency')"/>
                    <x-text-input id="q" name="q" value="{{ old('q') }}"></x-text-input>
                </div>
                <div>
                    <x-primary-button>{{ __('Submit') }}</x-primary-button>
                </div>
            </div>
        </form>
    </x-section>

    <x-section>
        <x-section-heading>
            {{ __('Currency List') }}
        </x-section-heading>
        <div class="space-y-6">
            @if ($currencies->isEmpty())
                <p>No currencies found!</p>
            @else
                <div>
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
                    </x-table.table>
                </div>
                <div>
                    {{ $currencies->links() }}
                </div>
            @endif
        </div>
    </x-section>
</x-app-layout>
