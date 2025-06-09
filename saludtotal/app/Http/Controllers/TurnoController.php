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
class TurnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('turnos.create');
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
        ])->save();

        return response()->json($turnoValidado);
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

    /**
     * Display the specified resource.
     */
    public function show(Turno $turno)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Turno $turno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTurnoRequest $request, Turno $turno)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turno $turno)
    {
        //
    }
}
