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
                    <form method="GET" action="{{ route('crypto.index') }}" id="search-form">
                        <x-input-label for="q" :value="__('Search for currency')"/>
                        <x-text-input id="q" name="q" value="{{ old('q') }}"></x-text-input>
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </form>

                    @if (count($currencies) === 0)
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
                                            {{ $currency->symbol() }}
                                        </x-table.data>
                                        <x-table.data>
                                            {{ $currency->exchangeRate() }}
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
