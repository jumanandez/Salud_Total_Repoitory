<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ApiTurnoController extends Controller
{
    /**
     * Buscar pacientes existentes
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarPacientes(Request $request)
    {
        try {
            $request->validate([
                'busqueda' => 'required|string|min:2',
            ]);

            $busqueda = $request->input('busqueda');
            
            // Buscar pacientes por nombre, apellido, email o DNI
            $pacientes = DB::table('pacientes')
                ->where(function($query) use ($busqueda) {
                    $query->where('nombre_apellido', 'like', "%{$busqueda}%")
                          ->orWhere('email', 'like', "%{$busqueda}%")
                          ->orWhere('dni', 'like', "%{$busqueda}%");
                })
                ->select('id', 'nombre_apellido', 'email', 'telefono', 'dni')
                ->limit(50) // Limitar resultados para mejor performance
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pacientes,
                'total' => $pacientes->count()
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Datos de entrada inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al buscar pacientes',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener todos los pacientes
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function listarPacientes()
    {
        try {
            $pacientes = DB::table('pacientes')
                ->select('id', 'nombre_apellido', 'email', 'telefono', 'dni')
                ->orderBy('nombre_apellido')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $pacientes,
                'total' => $pacientes->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al listar pacientes',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un turno para un paciente existente
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function crearTurno(Request $request)
    {
        try {
            // Validación de los datos
            $validated = $request->validate([
                'paciente_id' => 'required|integer|exists:pacientes,id',
                'doctor_id' => 'required|integer|exists:doctores,doctor_id',
                'fecha' => 'required|date|after_or_equal:today',
                'hora' => 'required|date_format:H:i',
            ]);

            DB::beginTransaction();

            // 1. Verificar que el paciente existe
            $paciente = DB::table('pacientes')
                ->where('id', $validated['paciente_id'])
                ->first();

            if (!$paciente) {
                throw new \Exception('El paciente seleccionado no existe');
            }

            // 2. Verificar disponibilidad del turno
            $turnoExistente = DB::table('turnos')
                ->where('doctor_id', $validated['doctor_id'])
                ->where('fecha', $validated['fecha'])
                ->where('hora', $validated['hora'])
                ->where('estado', '!=', 'cancelado')
                ->first();

            if ($turnoExistente) {
                throw new \Exception('El horario seleccionado ya está ocupado');
            }

            // 3. Verificar que el paciente no tenga otro turno a la misma hora
            $turnoMismaHora = DB::table('turnos')
                ->where('paciente_id', $validated['paciente_id'])
                ->where('fecha', $validated['fecha'])
                ->where('hora', $validated['hora'])
                ->where('estado', '!=', 'cancelado')
                ->first();

            if ($turnoMismaHora) {
                throw new \Exception('El paciente ya tiene un turno programado para esta fecha y hora');
            }

            // 4. Crear el turno
            $turnoId = DB::table('turnos')->insertGetId([
                'paciente_id' => $validated['paciente_id'],
                'doctor_id' => $validated['doctor_id'],
                'fecha' => $validated['fecha'],
                'hora' => $validated['hora'],
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 5. Obtener el turno completo con todas las relaciones
            $turnoCompleto = DB::table('turnos')
                ->join('pacientes', 'turnos.paciente_id', '=', 'pacientes.id')
                ->join('doctores', 'turnos.doctor_id', '=', 'doctores.doctor_id')
                ->join('especialidades', 'doctores.especialidad', '=', 'especialidades.especialidad_id')
                ->where('turnos.turno_id', $turnoId)
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
                    'pacientes.telefono as paciente_telefono',
                    'pacientes.dni as paciente_dni',
                    'doctores.doctor_id',
                    'doctores.nombre_apellido as doctor_nombre_apellido',
                    'especialidades.especialidad_id',
                    'especialidades.nombre as especialidad_nombre'
                )
                ->first();

            DB::commit();

            // 6. Transformar a la estructura esperada
            $turnoTransformado = [
                'id' => $turnoCompleto->id,
                'paciente_id' => $turnoCompleto->paciente_id,
                'doctor_id' => $turnoCompleto->doctor_id,
                'fecha' => $turnoCompleto->fecha,
                'hora' => $turnoCompleto->hora,
                'estado' => $turnoCompleto->estado,
                'paciente' => [
                    'id' => $turnoCompleto->paciente_id,
                    'nombre_apellido' => $turnoCompleto->paciente_nombre_apellido,
                    'email' => $turnoCompleto->paciente_email,
                    'telefono' => $turnoCompleto->paciente_telefono,
                    'dni' => $turnoCompleto->paciente_dni,
                ],
                'doctor' => [
                    'doctor_id' => $turnoCompleto->doctor_id,
                    'nombre_apellido' => $turnoCompleto->doctor_nombre_apellido,
                    'especialidad' => [
                        'especialidad_id' => $turnoCompleto->especialidad_id,
                        'nombre' => $turnoCompleto->especialidad_nombre
                    ]
                ]
            ];

            return response()->json([
                'success' => true,
                'message' => 'Turno creado exitosamente',
                'data' => $turnoTransformado
            ], 201);

        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'error' => 'Datos de entrada inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'error' => 'Error al crear el turno',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener información del paciente por ID
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerPaciente($id)
    {
        try {
            $paciente = DB::table('pacientes')
                ->where('id', $id)
                ->select('id', 'nombre_apellido', 'email', 'telefono', 'dni')
                ->first();

            if (!$paciente) {
                return response()->json([
                    'success' => false,
                    'error' => 'Paciente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $paciente
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener paciente',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
