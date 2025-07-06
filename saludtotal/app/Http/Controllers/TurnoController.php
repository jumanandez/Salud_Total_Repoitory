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
class TurnoController extends Controller
{


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('turnos.create');
    }
    public function index()
    {
        try {
            // Usar Query Builder con los nombres correctos de columnas (igual que en filterByEspecialidad)
            $turnos = DB::table('turnos')
                ->join('pacientes', 'turnos.paciente_id', '=', 'pacientes.id')
                ->join('doctores', 'turnos.doctor_id', '=', 'doctores.doctor_id')
                ->join('especialidades', 'doctores.especialidad', '=', 'especialidades.especialidad_id')
                ->select(
                    'turnos.turno_id as id',
                    'turnos.paciente_id',
                    'turnos.doctor_id',
                    'turnos.fecha',
                    'turnos.hora',
                    'turnos.estado',
                    'pacientes.id as paciente_id',
                    'pacientes.nombre_apellido as paciente_nombre_apellido',
                    'pacientes.email as paciente_email',
                    'doctores.doctor_id',
                    'doctores.nombre_apellido as doctor_nombre_apellido',
                    'especialidades.especialidad_id',
                    'especialidades.nombre as especialidad_nombre'
                )
                ->get();

            // Transformar a la estructura esperada (igual que en filterByEspecialidad)
            $turnosTransformados = $turnos->map(function ($turno) {
                return [
                    'id' => $turno->id,
                    'paciente_id' => $turno->paciente_id,
                    'doctor_id' => $turno->doctor_id,
                    'fecha' => $turno->fecha,
                    'hora' => $turno->hora,
                    'estado' => $turno->estado,
                    'paciente' => [
                        'id' => $turno->paciente_id,
                        'name' => $turno->paciente_nombre_apellido,
                        'email' => $turno->paciente_email,
                    ],
                    'doctor' => [
                        'doctor_id' => $turno->doctor_id,
                        'nombre_apellido' => $turno->doctor_nombre_apellido,
                        'especialidad' => [
                            'especialidad_id' => $turno->especialidad_id,
                            'nombre' => $turno->especialidad_nombre
                        ]
                    ]
                ];
            });

            return response()->json($turnosTransformados);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Datos de entrada inválidos',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al filtrar turnos por especialidad',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTurnoRequest $request)
    {
        $turnoValidado = $request->validated();
        $turnoNuevo = Turno::create([
            'paciente_id' => Auth::user()->id,
            'doctor_id' => $turnoValidado['doctor_id'],
            'fecha' => $turnoValidado['fecha'],
            'hora' => $turnoValidado['hora'],
            'estado' => 'activo'
        ]);

        return response()->json($turnoNuevo);
    }
    public function turnosDisponibles(Request $request)
    {

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
        return response()->json($slots);
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

        $turnos = Turno::where('paciente_id', $user->id)
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
            'cancelado_por' => Auth::user()->id,
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
            'cancelado_por' => Auth::user()->id,
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
                'reprogramado_por' => Auth::user()->id,
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
            $turno->update([
            'estado' => 'activo',
            'reprogramado' => true,
            'fecha_reprogramacion' => now(),
            'reprogramado_por' => Auth::user()->id,
            'fecha_edicion' => now(),
            ]);
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }

        return response()->json(['mensaje' => 'Reprogramación enviada correctamente.']);
    }
    /**
     * Filtrar turnos por especialidad
     */
    public function filterByEspecialidad(Request $request)
    {
        try {
            $validated = $request->validate([
                'especialidad_id' => 'required|integer|exists:especialidades,especialidad_id',
            ]);

            // Usar Query Builder con los nombres correctos de columnas
            $turnos = DB::table('turnos')
                ->join('pacientes', 'turnos.paciente_id', '=', 'pacientes.id')
                ->join('doctores', 'turnos.doctor_id', '=', 'doctores.doctor_id')
                ->join('especialidades', 'doctores.especialidad', '=', 'especialidades.especialidad_id')
                ->where('especialidades.especialidad_id', $validated['especialidad_id'])
                ->select(
                    'turnos.turno_id as id',  // Usar turno_id como id
                    'turnos.paciente_id',
                    'turnos.doctor_id',
                    'turnos.fecha',
                    'turnos.hora',
                    'turnos.estado',
                    'pacientes.id as paciente_id',
                    'pacientes.nombre_apellido as paciente_nombre_apellido',  // Campo correcto
                    'pacientes.email as paciente_email',
                    'doctores.doctor_id',
                    'doctores.nombre_apellido as doctor_nombre_apellido',
                    'especialidades.especialidad_id',
                    'especialidades.nombre as especialidad_nombre'
                )
                ->get();

            // Transformar a la estructura esperada
            $turnosTransformados = $turnos->map(function ($turno) {
                return [
                    'id' => $turno->id,
                    'paciente_id' => $turno->paciente_id,
                    'doctor_id' => $turno->doctor_id,
                    'fecha' => $turno->fecha,
                    'hora' => $turno->hora,
                    'estado' => $turno->estado,
                    'paciente' => [
                        'id' => $turno->paciente_id,
                        'name' => $turno->paciente_nombre_apellido,  // Usar el campo correcto
                        'email' => $turno->paciente_email,
                    ],
                    'doctor' => [
                        'doctor_id' => $turno->doctor_id,
                        'nombre_apellido' => $turno->doctor_nombre_apellido,
                        'especialidad' => [
                            'especialidad_id' => $turno->especialidad_id,
                            'nombre' => $turno->especialidad_nombre
                        ]
                    ]
                ];
            });

            return response()->json($turnosTransformados);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Datos de entrada inválidos',
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al filtrar turnos por especialidad',
                'message' => $e->getMessage()
            ], 500);
        }
    }


}
