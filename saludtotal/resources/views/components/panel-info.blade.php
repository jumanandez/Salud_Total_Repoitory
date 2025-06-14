<div class="relative flex flex-col justify-end w-full h-96 shadow-lg rounded-lg overflow-hidden mb-8 mx-4">
    <img
        src="{{ asset($img) }}"
        alt="{{ $title }}"
        class="absolute inset-0 w-full h-full object-cover object-center"
    />
    <div class="relative z-10 h-1/2 mt-auto p-6 bg-black-transparente text-white">
        <h3 class="text-xl font-semibold mb-2">{{ $title }}</h3>
        <p>{{ $slot }}</p>
    </div>
</div>
