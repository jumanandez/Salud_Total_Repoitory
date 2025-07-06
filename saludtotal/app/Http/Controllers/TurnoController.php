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
        $turnos = Turno::all();
        return response()->json($turnos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTurnoRequest $request)
    {
        $turnoValidado = $request->validated();
        $turnoNuevo = Turno::create([
            'paciente_id' => Auth::user()->paciente_id,
            'doctor_id' => $turnoValidado['doctor_id'],
            'fecha' => $turnoValidado['fecha'],
            'hora' => $turnoValidado['hora'],
            'estado' => 'activo'
        ]);
        $turnoNuevo->save();
        try{
            Mail::to(Auth::user()->email)->send(new NotificacionTurno(
                Auth::user()->nombre_apellido,
                $turnoNuevo['doctor_id'],
                $turnoNuevo->turno_id,
                $turnoNuevo['fecha'],
                $turnoNuevo['hora'],
            ));
        }catch (\Exception $e) {
            report($e); // opcional
            return response()->json(['mensaje' => 'Error al enviar correo', 'detalle' => $e->getMessage()], 500);
        }

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


}
