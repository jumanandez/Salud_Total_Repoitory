<div class="flex flex-row justify-between bg-gray-300 shadow hover:bg-gray-400 hover:shadow-lg py-2 px-4 rounded-lg">
    <p>
        <strong>{{ $label }}</strong> {{ $valor }}
    </p>
    @if ($valor == 'activo')
        <i class="fa-solid fa-circle-check text-xl text-green-800"></i>
    @elseif ($valor == 'pendiente')
        <i class="fa-solid fa-hourglass-half text-xl text-gray-700"></i>
    @elseif ($valor == 'cancelado')
        <i class="fa-solid fa-circle-xmark text-xl text-red-700"></i>
    @elseif ($valor == 'reprogramado')
        <i class="fa-solid fa-circle-arrow-up text-xl text-yellow-700"></i>
    @elseif ($valor == 'atendido')
        <i class="fa-solid fa-circle-arrow-down text-xl text-blue-800"></i>
    @endif
</div>
