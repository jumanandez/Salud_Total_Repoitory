<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Especialidad;
use App\Models\HorarioDisponible;
class ProfesionalesController extends Controller
{
    public function index()
    {
        return view('profesionales');
    }

    public function especialidades()
    {
        $especialidades = Especialidad::all();
        return response()->json($especialidades);
    }
    public function doctoresByEspecialidad($especialidadId)
    {
        $doctores = Doctor::query()->where('especialidad', $especialidadId)->get();
        return response()->json($doctores);
    }

    public function doctores()
    {
        $doctor = Doctor::all();
        return response()->json($doctor);
    }

    public function nombreDoctorById($id)
    {
        $nombreDoctor = Doctor::where('doctor_id', $id)->first('nombre_apellido');

        return response()->json($nombreDoctor);
    }
    public function horarios($doctorId)
    {
        $horarios = HorarioDisponible::query()->where('doctor_id', $doctorId)->where('activo', '1')->get();
        return response()->json($horarios);
    }

}
