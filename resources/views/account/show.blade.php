<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Viewing '{{ $account->name }}' bank account
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-bold px-6 py-4">
                        Account balance - {{ number_format($account->amount / 100, 2) . " {$account->currency}" }}
                    </h2>
                    @if($account->type === 'investment')
                        <h2 class="font-bold px-6 py-4">Portfolio</h2>
                        <h3 class="font-bold px-6 py-4">Sell currency</h3>
                        <form method="POST" action="{{ route('crypto-transaction.store') }}" id="transfer-form">
                            @csrf
                            <input hidden="hidden" id="type" name="type" value="sell">
                            <input hidden="hidden" id="account" name="account" value="{{ $account->iban }}">

                            <x-input-label for="currency" :value="__('Currency')"/>
                            <x-select id="currency" name="currency">
                                @foreach($cryptoPortfolioItems as $cryptoItem)
                                    <option value="{{ $cryptoItem->currency }}">
                                        {{
                                            $cryptoItem->currency . ' (' .
                                            number_format($cryptoItem->amount, 4) . ')'
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
                        <x-table.table>
                            <x-table.head>
                                <x-table.row>
                                    <x-table.header>
                                        {{ __('Currency') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Amount') }}
                                    </x-table.header>
                                </x-table.row>
                            </x-table.head>
                            <x-table.body>
                                @foreach($cryptoPortfolioItems as $cryptoItem)
                                    <x-table.row>
                                        <x-table.data>
                                            {{ $cryptoItem->currency }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $cryptoItem->amount }}
                                        </x-table.data>
                                    </x-table.row>
                                @endforeach
                            </x-table.body>
                        </x-table.table>
                        <h2 class="font-bold px-6 py-4">Investment history</h2>
                        <x-table.table>
                            <x-table.head>
                                <x-table.row>
                                    <x-table.header>
                                        {{ __('Transaction date') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Type') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Amount') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Symbol') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Price') }}
                                    </x-table.header>
                                </x-table.row>
                            </x-table.head>
                            <x-table.body>
                                @foreach($cryptoTransactions as $cryptoTransaction)
                                    <x-table.row>
                                        @php($selling = $cryptoTransaction->type === 'sell')
                                        <x-table.data>
                                            {{ $cryptoTransaction->created_at }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $cryptoTransaction->type }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $cryptoTransaction->amount }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $cryptoTransaction->currency }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $cryptoTransaction->price }}
                                        </x-table.data>
                                    </x-table.row>
                                @endforeach
                            </x-table.body>
                        </x-table.table>
                    @endif
                    @if(isset($moneyTransfers))
                        <h2 class="font-bold px-6 pt-8 pb-4">Transaction history</h2>
                        <x-table.table>
                            <x-table.head>
                                <x-table.row>
                                    <x-table.header>
                                        {{ __('Transaction date') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Sender / Receiver') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Note') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Transaction amount') }}
                                    </x-table.header>
                                </x-table.row>
                            </x-table.head>
                            <x-table.body>
                                @foreach($moneyTransfers as $moneyTransfer)
                                    <x-table.row>
                                        @php($receiving = $moneyTransfer->pivot->type === "receive")
                                        <x-table.data>
                                            {{ $moneyTransfer->created_at }}
                                        </x-table.data>
                                        <x-table.data>
                                            @if($receiving)
                                                {{ $moneyTransfer->accounts->first()->user->name }}
                                                {{ $moneyTransfer->accounts->first()->iban }}
                                            @else
                                                {{ $moneyTransfer->accounts->last()->user->name }}
                                                {{ $moneyTransfer->accounts->last()->iban }}
                                            @endif
                                        </x-table.data>
                                        <x-table.data>
                                            {{ Str::limit($moneyTransfer->note, 40) }}
                                        </x-table.data>
                                        <x-table.data class="{{$receiving ? '!text-green-500' : '!text-red-500' }}">
                                            {{ $receiving ? '+' : '-' }}
                                            {{ number_format(($receiving ? $moneyTransfer->amount_received : $moneyTransfer->amount_sent) / 100, 2) }}
                                            {{ $account->currency }}
                                        </x-table.data>
                                    </x-table.row>
                                @endforeach
                            </x-table.body>
                        </x-table.table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
