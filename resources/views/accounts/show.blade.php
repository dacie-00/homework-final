<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            '{{ $checkingAccount->name }}' transaction history
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-table.table>
                        <x-table.head>
                            <x-table.row>
                                <x-table.header>
                                    {{ __('Transaction date') }}
                                </x-table.header>
                                <x-table.header>
                                    {{ __('Transaction amount') }}
                                </x-table.header>
                                <x-table.header>
                                </x-table.header>
                            </x-table.row>
                        </x-table.head>
                        <x-table.body>
                            <x-table.row>
                                <x-table.data>
                                    Yesterday
                                </x-table.data>
                                <x-table.data>
                                    A lot
                                </x-table.data>
                            </x-table.row>
                        </x-table.body>
                    </x-table.table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
