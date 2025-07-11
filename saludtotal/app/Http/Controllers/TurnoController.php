<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTurnoRequest;
use App\Http\Requests\UpdateTurnoRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\HorarioDisponible;
use App\Rules\FechaDisponible;
use App\Rules\TurnoUnico;
use App\Rules\HorarioValido;
use Carbon\Carbon;
use App\ListarHorariosDisponibles;
use Illuminate\Support\Facades\DB;
use App\Models\SolicitudReprogramacion;
use App\Http\Requests\StoreSolicitudReprogramacionRequest;
use App\Mail\NotificacionTurno;
use App\Mail\MailTurnoAceptado;
use App\Mail\MailTurnoRechazado;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Enum\EstadoTurno;
use App\Enum\EstadoSolicitudReprogramacion;
use App\Http\Requests\ReprogramarTurnoRequest;
use Illuminate\Support\Facades\Validator;
class TurnoController extends Controller
{
    public function create()
    {
        return view('turnos.create');
    }
    public function index(Request $request)
    {
        $query = Turno::with(['paciente', 'doctor', 'especialidad']);

        // Filtro por doctor
        if ($request->filled('doctor')) {
            $query->whereHas('doctor', function ($q) use ($request) {
                $q->where('nombre_apellido', 'like', '%' . $request->doctor . '%');
            });
        }

        // Filtro por paciente
        if ($request->filled('paciente')) {
            $query->whereHas('paciente', function ($q) use ($request) {
                $q->where('nombre_apellido', 'like', '%' . $request->paciente . '%');
            });
        }

        // Filtro por fecha
        if ($request->filled('fecha')) {
            $query->where('fecha', $request->fecha);
        }

        if ($request->filled('desde') && $request->filled('hasta')) {
            $query->whereBetween('fecha', [$request->desde, $request->hasta]);
        } elseif ($request->filled('desde')) {
            $query->where('fecha', '>=', $request->desde);
        } elseif ($request->filled('hasta')) {
            $query->where('fecha', '<=', $request->hasta);
        }
        // Filtro por especialidad
        if ($request->filled('especialidad') && $request->especialidad !== 'todos') {
            $query->whereHas('especialidad', function ($q) use ($request) {
                $q->where('nombre', $request->especialidad);
            });
        }

        if($request->filled('estado') && $request->estado !== 'todos'){
            $query->where('estado', $request->estado);
        }

        // Orden personalizado por estado
        $query->orderByRaw("FIELD(estado,
        'pendiente', 'activo', 'aceptado','cancelado', 'rechazado', 'atendido','desaprovechado')");

        $turnos = $query->get();

        return response()->json($turnos);
    }

    public function getTurnoById($id) {
        $turno = Turno::with(['paciente', 'doctor', 'especialidad'])->find($id);

        if (!$turno) {
            return response()->json([
                'mensaje' => 'Turno no encontrado',
                'detalle' => "No existe un turno con ID $id"
            ], 404);
        }

        return response()->json([
            'mensaje' => 'Turno encontrado',
            'turno' => $turno]);
    }
    public function aceptarTurno(Request $request, $turno_id)
    {
        $turno = Turno::find($turno_id);

        if (!$turno) {
            return response()->json([
                'mensaje' => 'Turno no encontrado',
                'detalle' => "No existe un turno con ID $turno_id"
            ], 404);
        }

        if ($turno->estado !== EstadoTurno::PENDIENTE) {
            return response()->json([
                'mensaje' => 'No se puede aceptar un turno que no está pendiente',
                'detalle' => "El estado actual es '{$turno->estado->value}'"
            ], 400);
        }
        if($turno->solicita_reprogramacion){
            return response()->json([
                'mensaje' => 'Este turno ha solicitado reprogramación y no puede ser aceptado',
                'detalle' => "Revise La Seccion de Solicitudes de Reprogramación"
            ], 400);
        }
        if($turno->solicita_cancelacion){
            return response()->json([
                'mensaje' => 'Este turno ha solicitado cancelación y no puede ser aceptado',
                'detalle' => "Revise el Detalle del Turno"
            ], 400);
        }

        try{
            $turno->estado = EstadoTurno::ACEPTADO;
            $turno->save();

            Mail::to($turno->paciente->email)->send(new MailTurnoAceptado(
                $turno->paciente->nombre_apellido,
                $turno->turno_id,
                $turno->doctor_id,
                $turno->fecha,
                $turno->hora,
                $turno->estado->value
            ));
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json([
            'mensaje' => 'Turno aceptado correctamente',
        ],200);
    }
    public function rechazarTurno(Request $request, $turno_id)
    {
        $turno = Turno::find($turno_id);

        if (!$turno) {
            return response()->json([
                'mensaje' => 'Turno no encontrado',
                'detalle' => "No existe un turno con ID $turno_id"
            ], 404);
        }

        if ($turno->estado !== EstadoTurno::PENDIENTE) {
            return response()->json([
                'mensaje' => 'No se puede rechazar un turno que no está pendiente',
                'detalle' => "El estado actual es '{$turno->estado->value}'"
            ], 400);
        }

        try{
            $turno->estado = EstadoTurno::RECHAZADO;
            $turno->save();

            if(isset($request['mensaje']) && trim($request['mensaje']) !== ''){
                $mensaje = trim($request['mensaje']);

                Mail::to($turno->paciente->email)->send(new MailTurnoRechazado(
                    $turno->paciente->nombre_apellido,
                    $turno->turno_id,
                    $turno->doctor_id,
                    $turno->fecha,
                    $turno->hora,
                    $turno->estado->value,
                    $mensaje
                ));
            }
            else{
                Mail::to($turno->paciente->email)->send(new MailTurnoRechazado(
                    $turno->paciente->nombre_apellido,
                    $turno->turno_id,
                    $turno->doctor_id,
                    $turno->fecha,
                    $turno->hora,
                    $turno->estado->value
                ));
            }
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }
        return response()->json([
            'mensaje' => 'Turno rechazado correctamente',
        ],200);
    }
    public function cancelarTurno(Request $request, $turno_id)
    {
        $turnoACancelar = Turno::find($turno_id);
        if(!$turnoACancelar){
            return response()->json([
                'mensaje' => 'Turno no encontrado',
                'detalle' => "No se encontró un Turno con $turno_id"],
                404);
        }

        if($turnoACancelar->estado == EstadoTurno::CANCELADO){
            //Podria haber otras condiciones de estado
            return response()->json([
                'mensaje' => 'El Turno ya se encuentra Cancelado.',
                'detalle' => "El estado actual del Turno es '{$turnoACancelar->estado->value}'"
            ], 400);
        }else if($turnoACancelar->estado == EstadoTurno::ATENDIDO){
            return response()->json([
                'mensaje' => 'El Turno ya se encuentra Atendido.',
                'detalle' => "No se puede cancelar un Turno '{$turnoACancelar->estado->value}'"
            ], 400);
        }else if($turnoACancelar->estado == EstadoTurno::RECHAZADO){
            return response()->json([
                'mensaje' => 'El Turno ya se encuentra Rechazado.',
                'detalle' => "No se puede cancelar un Turno '{$turnoACancelar->estado->value}'"
            ], 400);
        }else if($turnoACancelar->estado == EstadoTurno::DESAPROVECHADO){
            return response()->json([
                'mensaje' => 'El Turno ya se encuentra Desaprovechado.',
                'detalle' => "No se puede cancelar un Turno '{$turnoACancelar->estado->value}'"
            ], 400);
        }

        try{
            $turnoACancelar->estado = EstadoTurno::CANCELADO;
            $turnoACancelar->canceled_at = now();
            $turnoACancelar->save();
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json([
            'mensaje' => 'Turno cancelado correctamente.'],
            200);
    }
    public function reprogramarTurno(Request $request, $turno_id)
    {
        $turno = Turno::find($turno_id);
        if(!$turno){
            return response()->json([
                'mensaje' => 'Turno no encontrado',
                'detalle' => "No se encontró un Turno con ID $turno_id"
            ], 404);
        }
        if($turno->estado == EstadoTurno::CANCELADO){
            return response()->json([
                'mensaje' => 'El Turno ya se encuentra Cancelado.',
                'detalle' => "No se puede Reprogramar un Turno '{$turno->estado->value}'"
            ], 400);
        }else if($turno->estado == EstadoTurno::ATENDIDO){
            return response()->json([
                'mensaje' => 'El Turno ya se encuentra Atendido.',
                'detalle' => "No se puede reprogramar un Turno '{$turno->estado->value}'"
            ], 400);
        }else if($turno->estado == EstadoTurno::RECHAZADO){
            return response()->json([
                'mensaje' => 'El Turno ya se encuentra Rechazado.',
                'detalle' => "No se puede reprogramar un Turno '{$turno->estado->value}'"
            ], 400);
        }else if($turno->estado == EstadoTurno::DESAPROVECHADO){
            return response()->json([
                'mensaje' => 'El Turno ya se encuentra Desaprovechado.',
                'detalle' => "No se puede reprogramar un Turno '{$turno->estado->value}'"
            ], 400);
        }

        try {
            $requestForm = new ReprogramarTurnoRequest();
            $requestForm->merge($request->all());

            $rules = $requestForm->rules();
            $messages = $requestForm->messages();
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                return response()->json([
                    'mensaje' => 'Validación fallida',
                    'errores' => $validator->errors()
                ], 422);
            }

            $validados = $validator->validated();

            $turno->fecha = $validados['fecha'];
            $turno->hora = $validados['hora'];
            $turno->estado = EstadoTurno::ACTIVO;
            $turno->reprogramado = true;
            $turno->save();

            return response()->json([
                'mensaje' => 'Turno Reprogramado correctamente',
                'turno' => $turno
            ]);
        }
        catch (\Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
            ], 422);
        }
    }
    public function crearTurno(StoreTurnoRequest $request)
    {
        $user = Auth::user();
        try{
            $turnoValidado = $request->validated();
            if(Auth::check()){
                $turnoNuevo = Turno::create([
                    'paciente_id' => $user->paciente_id,
                    'doctor_id' => $turnoValidado['doctor_id'],
                    'fecha' => $turnoValidado['fecha'],
                    'hora' => $turnoValidado['hora'],
                    'estado' => EstadoTurno::PENDIENTE
                ]);
                $turnoNuevo->save();
                Mail::to($user->email)
                ->send(new NotificacionTurno(
                    $user->nombre_apellido,
                    $turnoNuevo->turno_id,
                    $turnoNuevo->doctor_id,
                    $turnoNuevo->fecha,
                    $turnoNuevo->hora,
                    $turnoNuevo->estado->value
                ));
            }
            else{
                $turnoNuevo = Turno::create([
                    'paciente_id' => $turnoValidado['paciente_id'],
                    'doctor_id' => $turnoValidado['doctor_id'],
                    'fecha' => $turnoValidado['fecha'],
                    'hora' => $turnoValidado['hora'],
                    'estado' => 'activo'
                ]);
                $turnoNuevo->save();

                $user = User::find($turnoValidado['paciente_id']);
                Mail::to($user->email)
                ->send(new NotificacionTurno(
                    $user->nombre_apellido,
                    $turnoNuevo->turno_id,
                    $turnoNuevo->doctor_id,
                    $turnoNuevo->fecha,
                    $turnoNuevo->hora,
                    $turnoNuevo->estado->value
                ));
            }
        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'mensaje' => $e->getMessage(),
                'errores' => $e->errors()
            ], 422);
        }catch (\Exception $e) {
            report($e); // opcional
            return response()->json(['mensaje' => 'Error al enviar correo', 'detalle' => $e->getMessage()], 500);
        }
        return response()->json(['mensaje' => 'Turno creado Exitosamente',
        "Turno" => $turnoNuevo]);
    }

    public function turnosDisponibles(Request $request)
    {
        try {
            $validated = $request->validate([
                'doctor_id' => 'required|exists:doctores,doctor_id',
                'fecha' => ['required','date_format:Y-m-d','after_or_equal:today', new FechaDisponible($request['doctor_id'])],
            ],
            [
                'fecha.after_or_equal' => 'La fecha debe ser hoy o una fecha futura.',
            ]);

            $diaSemana = Carbon::parse($validated['fecha'], 'America/Argentina/Buenos_Aires')->dayOfWeekIso;

            $infoHorario = HorarioDisponible::where('doctor_id', $validated['doctor_id'])
                ->where('dia_semana', $diaSemana)
                ->first(['hora_inicio','hora_fin']);

            $duracion_slot = DB::table('tiempo_consulta')->where('doctor_id', $validated['doctor_id'])
                ->value('tiempo_minutos');

            $slots = ListarHorariosDisponibles::listarHorariosDisponibles(
                $infoHorario['hora_inicio'],
                $infoHorario['hora_fin'],
                $validated['fecha'],
                $validated['doctor_id'],
                $duracion_slot
            );
            if($slots == null){
                return response()->json([
                    'mensaje' => 'No hay turnos disponibles']);
            }
            if($slots == []){
                return response()->json([
                    'mensaje' => 'No hay turnos disponibles por count']);
            }
            return response()->json([
                'slots' =>$slots]);
        }catch (Exception $e) {
            return response()->json([
                'mensaje' => $e->getMessage()]);
        }
    }

    public function turnoDetails($turno_id)
    {
        $turno = Turno::find($turno_id);

        return view('turnos.turnoDetails', compact('turno'));
    }
    public function misTurnos(Request $request)
    {
        $user = auth()->user();

        $order = $request->get('orden', 'desc');

        $turnos = Turno::where('paciente_id', $user->paciente_id)
            ->when($request->estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->orderBy('fecha', $order)
            ->paginate(5)
            ->appends($request->query());

        return view('turnos.misTurnos', compact('turnos'));
    }
    public function getSolicitudesCancelacion(Request $request)
    {
        try{
            $solcitudesCancelacion = Turno::with(['paciente', 'doctor'])
                ->where('solicita_cancelacion', true)
                ->where('estado', EstadoTurno::PENDIENTE)
                ->get();

            if(!$solcitudesCancelacion){
                return response()->json([
                    'mensaje' => 'No se encontraron solicitudes de cancelación'
                ], 404);
            }

            return response()->json([
                'mensaje' => 'alto Mensaje',
                'solcitudes' => $solcitudesCancelacion
            ]);
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }
    }
    public function aceptarSolicitudCancelacion(Request $request, $turno_id)
    {
        try{
            $turno = Turno::find($turno_id);
            if(!$turno){
                return response()->json([
                    'mensaje' => 'Turno no encontrado',
                    'detalle' => "No existe un turno con ID $turno_id"
                ], 404);
            }
            $turno->update([
                'estado' => EstadoTurno::CANCELADO,
                'canceled_at' => now(),
            ]);
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json([
            'mensaje' => 'Solicitud de cancelación aceptada correctamente.',
            'turno' => $turno
        ]);
    }
    public function rechazarSolicitudCancelacion(Request $request, $turno_id)
    {
        try{
            $turno = Turno::find($turno_id);
            if(!$turno){
                return response()->json([
                    'mensaje' => 'Turno no encontrado',
                    'detalle' => "No existe un turno con ID $turno_id"
                ], 404);
            }
            $turno->update([
                'solicita_cancelacion' => false,
                'estado' => EstadoTurno::ACTIVO,
                'fecha_solicitud_cancelacion' => null,
                'cancelado_por' => null,
            ]);
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json([
            'mensaje' => 'Solicitud de cancelación rechazada correctamente.',
            'turno' => $turno
        ]);
    }
    public function solicitarCancelacion(Request $request, $turno_id)
    {
        try{
            $turno = Turno::find($turno_id);
            $turno->update([
            'estado' => EstadoTurno::PENDIENTE,
            'fecha_solicitud_cancelacion' => now(),
            'solicita_cancelacion' => true,
            'cancelado_por' => Auth::user()->paciente_id,
            ]);
            $turno->save();
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json([
            'mensaje' => 'Solicitud de cancelación enviada correctamente.',
            'turno' => $turno,
            'request' => $request
        ]);
    }

    public function getSolicitudesReprogramacion()
    {
        try{
            $solicitudes = SolicitudReprogramacion::with(['turno.paciente', 'turno.doctor'])
            ->where('estado', EstadoSolicitudReprogramacion::PENDIENTE)
                ->get();
            if(!$solicitudes){
                return response()->json([
                    'mensaje' => 'No se encontraron solicitudes de reprogramación'
                ], 404);
            }

            return response()->json([
                'solicitudes' => $solicitudes
            ]);
        }catch(Exception $e){
            return response()->json([
                'mensaje' => 'Error al obtener solicitudes de reprogramación.',
                'detalle' => $e->getMessage()],
                500);
        }
    }
    public function solicitarReprogramacion(StoreSolicitudReprogramacionRequest $request)
    {
        $validated = $request->validated();
        try{
            $solicitud = SolicitudReprogramacion::create([
                'turno_id' => $validated['turno_id'],
                'fecha' => $validated['nueva_fecha'],
                'hora' => $validated['nueva_hora'],
                'estado' => EstadoSolicitudReprogramacion::PENDIENTE
            ]);
            $solicitud->save();
            $turno = Turno::find($validated['turno_id']);
            $turno->update([
                'estado' => EstadoTurno::PENDIENTE,
                'solicita_reprogramacion' => true,
                'reprogramado_por' => Auth::user()->paciente_id,
            ]);
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json(['mensaje' => 'Solicitud de reprogramación enviada correctamente.',
                                'turno' => $solicitud]);
    }
    public function aceptarSolicitudReprogramacion(Request $request, $id)
    {
        try{
            $solicitud = SolicitudReprogramacion::find($id);
            if(!$solicitud){
                return response()->json([
                    'mensaje' => 'Solicitud no encontrada',
                    'detalle' => "No existe una solicitud con ID $id"
                ], 404);
            }

            $turno = Turno::find($solicitud->turno_id);
            if(!$turno){
                return response()->json([
                    'mensaje' => 'Turno no encontrado',
                    'detalle' => "No existe un turno asociado a la solicitud"
                ], 404);
            }
            $fechaa = Carbon::parse($solicitud->fecha, 'America/Argentina/Buenos_Aires');
            $datos = [
                'fecha' => $fechaa->format('Y-m-d'),
                'hora' => $solicitud->hora,
            ];
            $validador = Validator::make($datos, [
                'fecha' => [new TurnoUnico($turno->doctor_id, $datos['fecha'], $datos['hora'])],
                'hora' => [new HorarioValido($datos['fecha'], $datos['hora'], $turno->doctor_id)],
            ]);

            if ($validador->fails()) {
                return response()->json([
                    'mensaje' => 'Error al validar el turno.',
                    'errores' => $validador->errors(),
                ], 422);
            }

            $turno->fecha = $solicitud->fecha;
            $turno->hora = $solicitud->hora;
            $turno->estado = EstadoTurno::ACEPTADO;
            $turno->reprogramado = true;
            $turno->save();

            // Marcar la solicitud como aceptada
            $solicitud->estado = EstadoSolicitudReprogramacion::ACEPTADO;
            $solicitud->save();

        }
        catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'mensaje' => 'Validación fallida',
                'errores' => $e->errors()
            ], 422);

        }catch(Exception $e){
            return response()->json([
                'mensaje' => 'Error al aceptar la solicitud de reprogramación.',
                'detalle' => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'mensaje' => 'Solicitud de reprogramación aceptada correctamente.',
            'turno' => $turno]);
    }
    public function rechazarSolicitudReprogramacion (Request $request, $id)
    {
        try{
            $solicitud = SolicitudReprogramacion::find($id);
            if(!$solicitud){
                return response()->json([
                    'mensaje' => 'Solicitud no encontrada',
                    'detalle' => "No existe una solicitud con ID $id"
                ], 404);
            }
            $turno = Turno::find($solicitud->turno_id);
            if(!$turno){
                return response()->json([
                    'mensaje' => 'Turno no encontrado',
                    'detalle' => "No existe un turno asociado a la solicitud"
                ], 404);
            }

            $turno->estado = EstadoTurno::ACTIVO;
            // Marcar la solicitud como rechazada
            $solicitud->estado = EstadoSolicitudReprogramacion::RECHAZADO;
            $solicitud->save();



        }catch(Exception $e){
            return response()->json([
            'mensaje' => 'Error al rechazar la solicitud de reprogramación.',
            'detalle' => $e->getMessage()],
            500);
        }

        return response()->json(['mensaje' => 'Solicitud de reprogramación rechazada correctamente.',
                                'solicitud' => $solicitud]);
    }
    public function datosFormulario(Request $request)
    {
        try {
            // 1. Obtener todas las especialidades
            $especialidades = DB::table('especialidades')
                ->select('especialidad_id as id', 'nombre')
                ->orderBy('nombre')
                ->get();

            // 2. Obtener doctores agrupados por especialidad
            $doctoresPorEspecialidad = DB::table('doctores')
                ->join('especialidades', 'doctores.especialidad_id', '=', 'especialidades.especialidad_id')
                ->select(
                    'doctores.doctor_id as id',
                    'doctores.nombre_apellido as nombre_completo',
                    'doctores.especialidad_id as especialidad_id',
                    'especialidades.nombre as especialidad_nombre'
                )
                ->orderBy('especialidades.nombre')
                ->orderBy('doctores.nombre_apellido')
                ->get()
                ->groupBy('especialidad_id');

            // 3. Si se especifica doctor_id y fecha, obtener horarios disponibles
            $horariosDisponibles = [];
            if ($request->has('doctor_id') && $request->has('fecha')) {
                $validated = $request->validate([
                    'doctor_id' => 'required|exists:doctores,doctor_id',
                    'fecha' => 'required|date_format:Y-m-d|after_or_equal:today',
                ]);

                $diaSemana = \Carbon\Carbon::parse($validated['fecha'])->dayOfWeekIso;

                // CORREGIDO: Usar la tabla correcta disponibilidades_doctores
                $infoHorario = DB::table('disponibilidades_doctores')
                    ->where('doctor_id', $validated['doctor_id'])
                    ->where('dia_semana', $diaSemana)
                    ->where('estado', 1)
                    ->first(['hora_inicio', 'hora_fin']);

                if ($infoHorario) {
                    // Obtener duración de consulta de la tabla tiempo_consulta
                    $duracionSlot = DB::table('tiempo_consulta')
                        ->where('doctor_id', $validated['doctor_id'])
                        ->value('tiempo_minutos') ?? 30; // Default 30 minutos

                    // Generar slots disponibles
                    $horariosDisponibles = $this->generarHorariosDisponibles(
                        $infoHorario->hora_inicio,
                        $infoHorario->hora_fin,
                        $validated['fecha'],
                        $validated['doctor_id'],
                        $duracionSlot
                    );
                }
            }

            // 4. Estructurar respuesta
            $response = [
                'especialidades' => $especialidades->toArray(),
                'doctores_por_especialidad' => [],
                'horarios_disponibles' => $horariosDisponibles
            ];

            // Convertir la agrupación a un array más manejable
            foreach ($doctoresPorEspecialidad as $especialidadId => $doctores) {
                $response['doctores_por_especialidad'][] = [
                    'especialidad_id' => $especialidadId,
                    'especialidad_nombre' => $doctores->first()->especialidad_nombre,
                    'doctores' => $doctores->map(function ($doctor) {
                        return [
                            'id' => $doctor->id,
                            'nombre_completo' => $doctor->nombre_completo
                        ];
                    })->toArray()
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $response
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Parámetros inválidos',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener datos del formulario',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar horarios disponibles para un doctor en una fecha específica
     */
    private function generarHorariosDisponibles($horaInicio, $horaFin, $fecha, $doctorId, $duracionMinutos)
    {
        $horarios = [];

        try {
            // Convertir las horas (pueden venir como "08:00" o "08:00:00")
            $inicio = \Carbon\Carbon::createFromFormat('H:i', substr($horaInicio, 0, 5));
            $fin = \Carbon\Carbon::createFromFormat('H:i', substr($horaFin, 0, 5));

            // Obtener turnos ya ocupados para esa fecha y doctor
            $turnosOcupados = DB::table('turnos')
                ->where('doctor_id', $doctorId)
                ->where('fecha', $fecha)
                ->where('estado', '!=', 'cancelado')
                ->pluck('hora')
                ->map(function($hora) {
                    // Normalizar formato a H:i
                    return substr($hora, 0, 5);
                })
                ->toArray();

            while ($inicio->lessThan($fin)) {
                $horaSlot = $inicio->format('H:i');

                // Verificar si el slot no está ocupado
                if (!in_array($horaSlot, $turnosOcupados)) {
                    $horarios[] = [
                        'hora' => $horaSlot,
                        'disponible' => true,
                        'display' => $inicio->format('H:i')
                    ];
                }

                $inicio->addMinutes($duracionMinutos);
            }

        } catch (\Exception $e) {
            // En caso de error, devolver array vacío con log del error
            \Log::error('Error generando horarios disponibles: ' . $e->getMessage());
            return [];
        }

        return $horarios;
    }
}
