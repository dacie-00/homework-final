<x-app-layout title="Account Index - MockMiniBank">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <x-content>
        @if(session('success'))
            <div>
                {{ session('success') }}
            </div>
        @endif
        @if (isset($accounts))
            <x-table.table>
                <x-table.head>
                    <x-table.row>
                        <x-table.header>
                            {{ __('Account') }}
                        </x-table.header>
                        <x-table.header>
                            {{ __('Account type') }}
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
                    @foreach($accounts as $account)
                        <x-table.row>
                            <x-table.data>
                                {{ $account->iban }}
                            </x-table.data>
                            <x-table.data>
                                {{ ucfirst($account->type) }}
                            </x-table.data>
                            <x-table.data>
                                {{ $account->name }}
                            </x-table.data>
                            <x-table.data>
                                {{ number_format($account->amount / 100, 2) . ' ' .  $account->currency}}
                            </x-table.data>
                            <x-table.data>
                                <a
                                    href={{route('account.show', ['account' => $account->id])}}>
                                    {{ __('View info') }}
                                </a>
                            </x-table.data>
                        </x-table.row>
                    @endforeach
                </x-table.body>
            </x-table.table>
        @endif
    </x-content>
</x-app-layout>
