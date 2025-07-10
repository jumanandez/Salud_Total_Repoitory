<!DOCTYPE html>
<html lang="es" class="bg-gray-50">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Notificación de Salud Total</title>
</head>
<body class="bg-gray-50 font-sans text-gray-800">
  <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md mt-10">
    <h1 class="text-2xl font-semibold text-blue-600 mb-4">Notificación de Turno</h1>

    <p class="mb-4">Hola {{ $nombre }},</p>
    <p class="mb-2">Tu turno se ha creado correctamente. </p>
    @if($estado == 'Activo')
      <p class="mb-2">¡Gracias por elegirnos!</p>
    @elseif ($estado == 'Pendiente')
    <p class="mb-2">Proximamente te notificaremos la aceptación.</p>
    @endif
    <p class="mb-6"> Aquí están los detalles:</p>

    <dl class="space-y-4 border-t border-gray-200 pt-4">
      <div>
        <span class="font-semibold text-gray-600">ID del Turno: {{ $id_turno }}</span>
      </div>
      <div>
        <span class="font-semibold text-gray-600">Doctor: {{ $doctor }}</span>
      </div>
      <div>
        <span class="font-semibold text-gray-600">Fecha: {{ $fecha }}</span>
      </div>
      <div>
        <span class="font-semibold text-gray-600">Hora: {{ $hora }}</span>
      </div>
      <div>
        <span class="font-semibold text-gray-600">Estado: {{ $estado }}</span>
      </div>
    </dl>

    <p class="mt-6">Por favor, esta atento y contactanos si necesitás modificar algo.</p>

    <footer class="mt-10 text-center text-sm text-gray-500">
      &copy; {{ date('Y') }} Salud Total | Todos los derechos reservados
    </footer>
  </div>
</body>
</html>
