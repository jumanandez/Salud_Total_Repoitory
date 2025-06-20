<?php

namespace App\Http\Controllers;

use App\Models\Consulta;
use Illuminate\Http\Request;
use App\Http\Requests\StoreConsultaRequest;

class ConsultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConsultaRequest $request)
    {
        $validatedData = $request->validated();

        $consulta = Consulta::create([
            'nombre_apellido' => $validatedData['nombre_apellido'],
            'email' => $validatedData['email'],
            'telefono' => $validatedData['telefono'],
            'mensaje' => $validatedData['mensaje'],
        ])->save();

        // return redirect()->back()->with('success', 'Se ha enviado la consulta');
        return response()->json(['mensaje' => 'Se ha enviado la consulta']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Consulta $consulta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Consulta $consulta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consulta $consulta)
    {
        //
    }
}
