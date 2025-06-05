<div class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
    <img
        class="w-full h-48 object-cover object-center"
        src="{{ asset($img) }}"
        alt="{{ $title }}"
    />
    <div class="p-6">
        <h3 class="text-xl font-semibold mb-2">{{ $title }}</h3>
        <p class="text-gray-800">{{ $slot }}</p>
    </div>
</div>
