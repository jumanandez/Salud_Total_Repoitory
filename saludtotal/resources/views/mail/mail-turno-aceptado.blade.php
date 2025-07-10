<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Turno Aceptado - SaludTotal</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 py-8">
  <div class="max-w-xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold text-blue-600 mb-4">SaludTotal</h1>
    <h2 class="text-xl font-semibold text-gray-800 mb-2">¡Tu turno fue aceptado, {{ $nombre }}!</h2>

    <p class="text-gray-700 mb-4">
      Estimado/a paciente,<br>
      Te informamos que tu solicitud de turno ha sido aceptada por nuestro sistema. A continuación, los detalles:
    </p>

    <div class="bg-blue-50 p-4 rounded-md border border-blue-200 mb-6">
        <p><strong>🔖 ID del Turno:</strong> {{ $id_turno }}</p>
        <p><strong>👨‍⚕️ Doctor:</strong> Dr. {{ $doctor }}</p>
        <p><strong>📅 Fecha:</strong> {{ $fecha }}</p>
        <p><strong>⏰ Hora:</strong> {{ $hora }}</p>
        <p><strong>📌 Estado:</strong> <span class="text-green-600 font-semibold">{{ $estado }}</span></p>
    </div>

    <p class="text-gray-700 mb-4">
      Te recomendamos llegar 10 minutos antes del horario establecido y traer tu documentación correspondiente.
    </p>

    <p class="text-sm text-gray-500 mt-6">
      Este es un mensaje automático enviado por el sistema de turnos de <strong>SaludTotal</strong>. Si no solicitaste este turno, comunícate con nosotros.
    </p>
  </div>
</body>
</html>

