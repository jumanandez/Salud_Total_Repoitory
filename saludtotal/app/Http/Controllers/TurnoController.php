<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTurnoRequest;
use App\Http\Requests\UpdateTurnoRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\HorarioDisponible;
use App\Rules\FechaDisponible;
use Carbon\Carbon;
use App\ListarHorariosDisponibles;
use Illuminate\Support\Facades\DB;
use App\Models\solicitudReprogramacion;
use App\Http\Requests\StoreSolicitudReprogramacionRequest;
use App\Mail\NotificacionTurno;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
class TurnoController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
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

        // Filtro por especialidad
        if ($request->filled('especialidad') && $request->especialidad !== 'todos') {
            $query->whereHas('especialidad', function ($q) use ($request) {
                $q->where('nombre', $request->especialidad);
            });
        }

        $turnos = $query->get();

        return response()->json($turnos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTurnoRequest $request)
    {
        $user = Auth::user();
        try{
            $turnoValidado = $request->validated();
            $turnoNuevo = Turno::create([
                'paciente_id' => $user->paciente_id,
                'doctor_id' => $turnoValidado['doctor_id'],
                'fecha' => $turnoValidado['fecha'],
                'hora' => $turnoValidado['hora'],
                'estado' => 'activo'
            ]);
            $turnoNuevo->save();
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
    public function storeDesktop(Request $request)
    {

        // try {
        //     // Validación de los datos
        //     $validated = $request->validate([
        //         // Datos del paciente
        //         'paciente_nombre_apellido' => 'required|string|max:255',
        //         'paciente_telefono' => 'nullable|string|max:20',
        //         'paciente_email' => 'required|email|max:255',

        //         // Datos del turno
        //         'doctor_id' => 'required|integer|exists:doctores,doctor_id',
        //         'fecha' => 'required|date|after_or_equal:today',
        //         'hora' => 'required|date_format:H:i',
        //         'especialidad_id' => 'required|integer|exists:especialidades,especialidad_id',
        //     ]);

        //     DB::beginTransaction();

        //     // 1. Verificar si el paciente ya existe por email
        //     $paciente = DB::table('pacientes')
        //         ->where('email', $validated['paciente_email'])
        //         ->first();

        //     // 2. Si no existe, crear el paciente
        //     if (!$paciente) {
        //         $pacienteId = DB::table('pacientes')->insertGetId([
        //             'nombre_apellido' => $validated['paciente_nombre_apellido'],
        //             'telefono' => $validated['paciente_telefono'] ?? null,
        //             'email' => $validated['paciente_email'],
        //             'created_at' => now(),
        //             'updated_at' => now(),
        //         ]);
        //     } else {
        //         $pacienteId = $paciente->id;
        //     }

        //     // 3. Verificar disponibilidad del turno
        //     $turnoExistente = DB::table('turnos')
        //         ->where('doctor_id', $validated['doctor_id'])
        //         ->where('fecha', $validated['fecha'])
        //         ->where('hora', $validated['hora'])
        //         ->where('estado', '!=', 'cancelado')
        //         ->first();

        //     if ($turnoExistente) {
        //         throw new \Exception('El horario seleccionado ya está ocupado');
        //     }

        //     // 4. Crear el turno
        //     $turnoId = DB::table('turnos')->insertGetId([
        //         'paciente_id' => $pacienteId,
        //         'doctor_id' => $validated['doctor_id'],
        //         'fecha' => $validated['fecha'],
        //         'hora' => $validated['hora'],
        //         'estado' => 'activo',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);

        //     // 5. Obtener el turno completo con todas las relaciones
        //     $turnoCompleto = DB::table('turnos')
        //         ->join('pacientes', 'turnos.paciente_id', '=', 'pacientes.paciente_id')
        //         ->join('doctores', 'turnos.doctor_id', '=', 'doctores.doctor_id')
        //         ->join('especialidades', 'doctores.especialidad_id', '=', 'especialidades.especialidad_id')
        //         ->where('turnos.turno_id', $turnoId)
        //         ->select(
        //             'turnos.turno_id as id',
        //             'turnos.paciente_id',
        //             'turnos.doctor_id',
        //             'turnos.fecha',
        //             'turnos.hora',
        //             'turnos.estado',
        //             'pacientes.paciente_id',
        //             'pacientes.nombre_apellido as paciente_nombre_apellido',
        //             'pacientes.email as paciente_email',
        //             'pacientes.telefono as paciente_telefono',
        //             'doctores.doctor_id',
        //             'doctores.nombre_apellido as doctor_nombre_apellido',
        //             'especialidades.especialidad_id',
        //             'especialidades.nombre as especialidad_nombre'
        //         )
        //         ->first();

        //     DB::commit();

        //     // 6. Transformar a la estructura esperada
        //     $turnoTransformado = [
        //         'id' => $turnoCompleto->id,
        //         'paciente_id' => $turnoCompleto->paciente_id,
        //         'doctor_id' => $turnoCompleto->doctor_id,
        //         'fecha' => $turnoCompleto->fecha,
        //         'hora' => $turnoCompleto->hora,
        //         'estado' => $turnoCompleto->estado,
        //         'paciente' => [
        //             'id' => $turnoCompleto->paciente_id,
        //             'name' => $turnoCompleto->paciente_nombre_apellido,
        //             'email' => $turnoCompleto->paciente_email,
        //             'telefono' => $turnoCompleto->paciente_telefono,
        //         ],
        //         'doctor' => [
        //             'doctor_id' => $turnoCompleto->doctor_id,
        //             'nombre_apellido' => $turnoCompleto->doctor_nombre_apellido,
        //             'especialidad' => [
        //                 'especialidad_id' => $turnoCompleto->especialidad_id,
        //                 'nombre' => $turnoCompleto->especialidad_nombre
        //             ]
        //         ]
        //     ];

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Turno creado exitosamente',
        //         'data' => $turnoTransformado
        //     ], 201);

        // } catch (\Illuminate\Validation\ValidationException $e) {
        //     DB::rollback();
        //     return response()->json([
        //         'success' => false,
        //         'error' => 'Datos de entrada inválidos',
        //         'message' => 'Por favor verifica los datos ingresados',
        //         'errors' => $e->errors()
        //     ], 422);
        // } catch (\Exception $e) {
        //     DB::rollback();
        //     return response()->json([
        //         'success' => false,
        //         'error' => 'Error al crear el turno',
        //         'message' => $e->getMessage()
        //     ], 500);
        // }
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
                return response()->json(['mensaje' => 'No hay turnos disponibles']);
            }
            if($slots == []){
                return response()->json(['mensaje' => 'No hay turnos disponibles por count']);
            }
            return response()->json(['slots' =>$slots]);
        }catch (Exception $e) {
            return response()->json(['mensaje' => $e->getMessage()]);
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

    public function solicitarCancelacion(Request $request, $turno_id)
    {
        try{
            $turno = Turno::find($turno_id);
            $turno->
            update([
            'estado' => 'pendiente',
            'fecha_solicitud_cancelacion' => now(),
            'solicita_cancelacion' => true,
            'cancelado_por' => Auth::user()->paciente_id,
            'fecha_edicion' => now(),
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
    public function cancelar(Turno $turno)
    {
        try{
            $turno->update([
            'estado' => 'cancelado',
            'fecha_cancelacion' => now(),
            'cancelado_por' => Auth::user()->paciente_id,
            'fecha_edicion' => now(),
            ]);
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json(['mensaje' => 'Turno cancelado correctamente.']);
    }
    public function solicitarReprogramacion(StoreSolicitudReprogramacionRequest $request)
    {
        $validated = $request->validated();
        try{
            $solicitud = solicitudReprogramacion::create([
                'turno_id' => $validated['turno_id'],
                'fecha' => $validated['nueva_fecha'],
                'hora' => $validated['nueva_hora'],
                'estado' => 'pendiente'
            ]);
            $solicitud->save();
            $turno = Turno::find($validated['turno_id']);
            $turno->update([
                'estado' => 'pendiente',
                'fecha_solicitud_reprogramacion' => now(),
                'solicita_reprogramacion' => true,
                'reprogramado_por' => Auth::user()->paciente_id,
            ]);
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json(['mensaje' => 'Solicitud de reprogramación enviada correctamente.',
                                'turno' => $solicitud]);
    }
    public function reprogramar(Turno $turno)
    {
        try{
            //USAR EL PACIENTE_ID DEL TURNO
            // $turno->update([
            // 'estado' => 'activo',
            // 'reprogramado' => true,
            // 'fecha_reprogramacion' => now(),
            // 'reprogramado_por' => Auth::user()->paciente_id,
            // 'fecha_edicion' => now(),
            // ]);
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json(['mensaje' => 'Reprogramación enviada correctamente.']);
    }
    /**
     * Obtener todos los datos necesarios para el formulario de creación de turnos
     */
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
                    ->where('activo', 1)
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
