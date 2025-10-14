<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\HorarioDisponible;
use App\Models\MotivoDeAusencia;
use App\Models\AusenciasDoctor;
class ProfesionalesController extends Controller
{
    public function index()
    {
        return view('profesionales');
    }

    public function especialidades()
    {
        $especialidades = Especialidad::all();
        return response()->json(['especialidades' => $especialidades]);
    }
    public function doctoresByEspecialidad($especialidadId)
    {
        try{
        $doctores = Doctor::where('especialidad_id', $especialidadId)->get();
        }catch(Exception $e){
            return response()->json(['mensaje' => $e->getMessage()]);
        }
        return response()->json(['doctores_by_especialidad' => $doctores]);
    }

    public function doctores()
    {
        $doctor = Doctor::with('especialidad')->get();
        return response()->json(['doctores' => $doctor]);
    }

    public function nombreDoctorById($id)
    {
        $nombreDoctor = Doctor::where('doctor_id', $id)->first('nombre_apellido');

        return response()->json($nombreDoctor);
    }
    public function horarios($doctorId)
    {
        $horarios = HorarioDisponible::query()->where('doctor_id', $doctorId)->where('estado', '1')->get();
        return response()->json(['horarios_laborales' =>$horarios]);
    }

    public function getAusenciasByDoctor($doctorId)
    {
        $doctor = Doctor::find($doctorId);
        if(!$doctor) {
            return response()->json(['mensaje' => 'Doctor no encontrado'], 404);
        }

        $ausencias = $doctor->ausencias()->with('motivo')->orderByDesc('created_at')->get();

        return response()->json(['ausencias' => $ausencias]);
    }

    public function getMotivosAusencia()
    {
        $motivos = MotivoDeAusencia::all();
        return response()->json(['motivos' => $motivos]);
    }
}
