<x-app-layout title="Account Index - MockMiniBank">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @if (isset($accounts))
        <x-section class="mt-8">
            <x-section-heading>
                {{ __('Account List') }}
            </x-section-heading>
            <div class="text-center">
                @if(session('success'))
                    <div>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    {!! implode('', $errors->all('<div class="text-red-400">:message</div>')) !!}
                @endif
            </div>
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
                                <a class="text-blue-500 hover:underline"
                                   href={{route('account.show', ['account' => $account])}}>
                                    {{ __('View') }}
                                </a>
                            </x-table.data>
                            <x-table.data>
                                <form action="{{ route('account.delete', $account) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input class="text-red-400 cursor-pointer" type="submit" value="Delete">
                                </form>
                            </x-table.data>
                        </x-table.row>
                    @endforeach
                </x-table.body>
            </x-table.table>
        </x-section>
    @endif
</x-app-layout>
