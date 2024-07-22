<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Viewing '{{ $checkingAccount->name }}' bank account
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h2 class="font-bold px-6 py-4">Transaction history</h2>
                    @if(isset($moneyTransfers))
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
                                        @php($positive = $moneyTransfer->pivot->type === "receive")
                                        <x-table.data>
                                            {{ $moneyTransfer->created_at }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $moneyTransfer->checkingAccounts->first()->user->name }}
{{--                                            {{ $moneyTransfer->checkingAccounts->where("id", "!==", $checkingAccount->id)->first()->user->name}}--}}
                                        </x-table.data>
                                        <x-table.data>
                                            Here's some free money
                                        </x-table.data>
                                        <x-table.data class="{{$positive ? '!text-green-500' : '!text-red-500' }}">
                                            {{ $positive ? "+" : "-" }}{{ number_format($moneyTransfer->amount_sent, 2) }}
                                            {{ $checkingAccount->currency }}
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
