<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (isset($checkingAccounts))
                        <x-table.table>
                            <x-table.head>
                                <x-table.row>
                                    <x-table.header>
                                        {{ __('Account') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Name') }}
                                    </x-table.header>
                                    <x-table.header>
                                        {{ __('Available funds') }}
                                    </x-table.header>
                                    <x-table.header>
                                    </x-table.header>
                                </x-table.row>
                            </x-table.head>
                            <x-table.body>
                                @foreach($checkingAccounts as $checkingAccount)
                                    <x-table.row>
                                        <x-table.data>
                                            {{ $checkingAccount->iban }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $checkingAccount->name }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ number_format($checkingAccount->amount / 100, 2) . " " .  $checkingAccount->currency}}
                                        </x-table.data>
                                        <x-table.data>
                                            <a
                                                href={{route('accounts.show', ['checkingAccount' => $checkingAccount->id])}}>
                                                {{ __('View info') }}
                                            </a>
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
