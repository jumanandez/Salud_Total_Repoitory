<a href="{{ $href }}" class='bg-white border-2 border-blue-900 rounded-lg shadow p-2 flex flex-col items-center mx-10 w-40 hover:bg-blue-200 cursor-pointer transition duration-300'>
    <img src="{{ asset($img) }}" alt="{{ $label }}"
        class="w-16 h-16 md:w-14 md:h-14 sm:w-12 sm:h-12"/>
    <p class="mt-2 text-center text-lg md:text-base sm:text-sm font-bold">{{ $label }}</p>
</a>
