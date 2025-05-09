<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Especialidad;
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


}
