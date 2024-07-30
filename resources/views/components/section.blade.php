<div {{ $attributes->merge( ['class' => 'py-2']) }}>
    <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 space-y-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
