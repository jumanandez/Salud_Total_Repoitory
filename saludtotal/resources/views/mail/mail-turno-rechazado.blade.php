<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Turno Rechazado - SaludTotal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 py-8">
  <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold text-red-600 mb-4">SaludTotal</h1>
    <h2 class="text-xl font-semibold text-gray-800 mb-2">{{$nombre}}, Tu solicitud de turno fue rechazada</h2>

    <p class="text-gray-700 mb-4">
      Estimado/a paciente,<br>
      Lamentamos informarte que el turno que solicitaste no ha podido ser confirmado. A continuación, te brindamos los detalles:
    </p>

    <div class="bg-red-50 p-4 rounded-md border border-red-200 mb-6">
        <p><strong>🔖 ID de Turno:</strong> {{ $turno_id }}</p>
        <p><strong>👨‍⚕️ Doctor:</strong> Dr. {{ $doctor }}</p>
        <p><strong>📅 Fecha:</strong> {{ $fecha }}</p>
        <p><strong>⏰ Hora:</strong> {{ $hora }}</p>
        <p><strong>📌 Estado:</strong> <span class="text-red-600 font-semibold">Rechazado</span></p>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-md mb-6">
      <p class="font-semibold text-yellow-700 mb-2">📝 Mensaje de la clínica:</p>
      <p class="text-gray-800 italic">
        "{{ $mensaje }}"
      </p>
    </div>

    <p class="text-gray-700">
      Te invitamos a ingresar nuevamente al sistema para solicitar otro turno en un horario disponible, o comunicarte con nuestra recepción para más asistencia.
    </p>

    <p class="text-sm text-gray-500 mt-6">
      Este es un mensaje automático enviado por el sistema de turnos de <strong>SaludTotal</strong>.
    </p>
  </div>
</body>
</html>
