<div {{ $attributes->merge( ['class' => 'py-4 w-full']) }}>
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg shadow-xl">
            <div class="p-8 text-gray-900 space-y-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
