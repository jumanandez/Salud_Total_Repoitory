<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Turno;
use App\Models\HorarioDisponible;
use App\Enum\EstadoTurno;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * Controlador para manejar las estadísticas de turnos médicos
 * 
 * Este controlador proporciona endpoints para obtener estadísticas
 * de turnos tanto por doctor individual como globalmente.
 * 
 * Funcionalidades incluidas:
 * - Estadísticas por doctor específico
 * - Estadísticas globales de todos los doctores
 * - Estadísticas con filtros de fecha
 * - Cálculo de ausencias por doctor
 */
class EstadisticasController extends Controller
{
    /**
     * Obtener estadísticas de turnos para un doctor específico
     *
     * @param int $doctorId
     * @return JsonResponse
     */
    public function estadisticasPorDoctor($doctorId): JsonResponse
    {
        try {
            // Verificar que el doctor existe
            $doctor = Doctor::find($doctorId);
            if (!$doctor) {
                return response()->json([
                    'error' => 'Doctor no encontrado'
                ], 404);
            }

            // Obtener estadísticas de turnos por estado
            $estadisticasTurnos = Turno::select('estado', DB::raw('count(*) as cantidad'))
                ->where('doctor_id', $doctorId)
                ->groupBy('estado')
                ->get()
                ->keyBy('estado');

            // Formatear las estadísticas según los estados solicitados
            $estadisticas = [
                'turnosAtendidos' => $estadisticasTurnos->get(EstadoTurno::ATENDIDO->value)?->cantidad ?? 0,
                'turnosCancelados' => $estadisticasTurnos->get(EstadoTurno::CANCELADO->value)?->cantidad ?? 0,
                'turnosRechazados' => $estadisticasTurnos->get(EstadoTurno::RECHAZADO->value)?->cantidad ?? 0,
                'turnosReprogramados' => $this->contarTurnosReprogramados($doctorId),
                'turnosAceptados' => $estadisticasTurnos->get(EstadoTurno::ACEPTADO->value)?->cantidad ?? 0,
                'turnosDesaprovechados' => $estadisticasTurnos->get(EstadoTurno::DESAPROVECHADO->value)?->cantidad ?? 0,
            ];

            // Calcular ausencias (días sin trabajo)
            $ausencias = $this->calcularAusencias($doctorId);

            return response()->json([
                'doctor_id' => $doctorId,
                'doctor' => [
                    'id' => $doctor->doctor_id,
                    'nombre' => $doctor->nombre_apellido ?? null,
                    'especialidad' => $doctor->especialidad->nombre ?? null
                ],
                'estadisticas' => $estadisticas,
                'ausencias' => $ausencias
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener estadísticas del doctor',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas globales de todos los doctores
     *
     * @return JsonResponse
     */
    public function estadisticasGlobales(): JsonResponse
    {
        try {
            // Obtener estadísticas globales de turnos por estado
            $estadisticasGlobales = Turno::select('estado', DB::raw('count(*) as cantidad'))
                ->groupBy('estado')
                ->get()
                ->keyBy('estado');

            // Formatear las estadísticas según los estados solicitados
            $estadisticas = [
                'turnosAtendidos' => $estadisticasGlobales->get(EstadoTurno::ATENDIDO->value)?->cantidad ?? 0,
                'turnosCancelados' => $estadisticasGlobales->get(EstadoTurno::CANCELADO->value)?->cantidad ?? 0,
                'turnosRechazados' => $estadisticasGlobales->get(EstadoTurno::RECHAZADO->value)?->cantidad ?? 0,
                'turnosReprogramados' => $this->contarTurnosReprogramadosGlobal(),
                'turnosAceptados' => $estadisticasGlobales->get(EstadoTurno::ACEPTADO->value)?->cantidad ?? 0,
                'turnosDesaprovechados' => $estadisticasGlobales->get(EstadoTurno::DESAPROVECHADO->value)?->cantidad ?? 0,
            ];

            // Calcular ausencias globales
            $ausenciasGlobales = $this->calcularAusenciasGlobales();

            // Total de doctores
            $totalDoctores = Doctor::count();

            return response()->json([
                'estadisticas' => $estadisticas,
                'ausenciasGlobales' => $ausenciasGlobales,
                'totalDoctores' => $totalDoctores
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener estadísticas globales',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Contar turnos reprogramados para un doctor específico
     *
     * @param int $doctorId
     * @return int
     */
    private function contarTurnosReprogramados($doctorId): int
    {
        return Turno::where('doctor_id', $doctorId)
            ->where('reprogramado', true)
            ->count();
    }

    /**
     * Contar turnos reprogramados globalmente
     *
     * @return int
     */
    private function contarTurnosReprogramadosGlobal(): int
    {
        return Turno::where('reprogramado', true)->count();
    }

    /**
     * Calcular ausencias para un doctor específico
     * Cuenta los días en los que el doctor tenía horarios disponibles pero no tuvo turnos
     *
     * @param int $doctorId
     * @return int
     */
    private function calcularAusencias($doctorId): int
    {
        try {
            // Obtener fechas únicas de horarios disponibles del doctor
            $fechasConHorarios = HorarioDisponible::where('doctor_id', $doctorId)
                ->whereNotNull('fecha')
                ->select('fecha')
                ->distinct()
                ->pluck('fecha')
                ->toArray();

            // Obtener fechas únicas donde el doctor tuvo turnos
            $fechasConTurnos = Turno::where('doctor_id', $doctorId)
                ->whereNotNull('fecha')
                ->select('fecha')
                ->distinct()
                ->pluck('fecha')
                ->toArray();

            // Contar días con horarios disponibles pero sin turnos
            $ausencias = 0;
            foreach ($fechasConHorarios as $fecha) {
                if (!in_array($fecha, $fechasConTurnos)) {
                    $ausencias++;
                }
            }

            return $ausencias;
        } catch (\Exception $e) {
            // En caso de error, retornar 0
            return 0;
        }
    }

    /**
     * Calcular ausencias globales de todos los doctores
     *
     * @return int
     */
    private function calcularAusenciasGlobales(): int
    {
        $doctores = Doctor::all();
        $ausenciasGlobales = 0;

        foreach ($doctores as $doctor) {
            $ausenciasGlobales += $this->calcularAusencias($doctor->doctor_id);
        }

        return $ausenciasGlobales;
    }

    /**
     * Obtener estadísticas detalladas por doctor (listado completo)
     *
     * @return JsonResponse
     */
    public function estadisticasPorTodosLosDoctores(): JsonResponse
    {
        try {
            $doctores = Doctor::with('especialidad')->get();
            $estadisticasPorDoctor = [];

            foreach ($doctores as $doctor) {
                // Obtener estadísticas de turnos por estado para este doctor
                $estadisticasTurnos = Turno::select('estado', DB::raw('count(*) as cantidad'))
                    ->where('doctor_id', $doctor->doctor_id)
                    ->groupBy('estado')
                    ->get()
                    ->keyBy('estado');

                // Formatear las estadísticas
                $estadisticas = [
                    'turnosAtendidos' => $estadisticasTurnos->get(EstadoTurno::ATENDIDO->value)?->cantidad ?? 0,
                    'turnosCancelados' => $estadisticasTurnos->get(EstadoTurno::CANCELADO->value)?->cantidad ?? 0,
                    'turnosRechazados' => $estadisticasTurnos->get(EstadoTurno::RECHAZADO->value)?->cantidad ?? 0,
                    'turnosReprogramados' => $this->contarTurnosReprogramados($doctor->doctor_id),
                    'turnosAceptados' => $estadisticasTurnos->get(EstadoTurno::ACEPTADO->value)?->cantidad ?? 0,
                    'turnosDesaprovechados' => $estadisticasTurnos->get(EstadoTurno::DESAPROVECHADO->value)?->cantidad ?? 0,
                ];

                // Calcular ausencias
                $ausencias = $this->calcularAusencias($doctor->doctor_id);

                $estadisticasPorDoctor[] = [
                    'doctor_id' => $doctor->doctor_id,
                    'doctor' => [
                        'id' => $doctor->doctor_id,
                        'nombre' => $doctor->nombre_apellido ?? null,
                        'especialidad' => $doctor->especialidad->nombre ?? null
                    ],
                    'estadisticas' => $estadisticas,
                    'ausencias' => $ausencias
                ];
            }

            return response()->json([
                'doctores' => $estadisticasPorDoctor
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener estadísticas por doctor',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estadísticas por rango de fechas para un doctor específico
     *
     * @param Request $request
     * @param int $doctorId
     * @return JsonResponse
     */
    public function estadisticasPorDoctorConFechas(Request $request, $doctorId): JsonResponse
    {
        try {
            // Verificar que el doctor existe
            $doctor = Doctor::find($doctorId);
            if (!$doctor) {
                return response()->json([
                    'error' => 'Doctor no encontrado'
                ], 404);
            }

            // Obtener fechas del request (opcional)
            $fechaInicio = $request->input('fecha_inicio');
            $fechaFin = $request->input('fecha_fin');

            // Construir query base
            $query = Turno::where('doctor_id', $doctorId);

            // Aplicar filtros de fecha si se proporcionan
            if ($fechaInicio) {
                $query->where('fecha', '>=', $fechaInicio);
            }
            if ($fechaFin) {
                $query->where('fecha', '<=', $fechaFin);
            }

            // Obtener estadísticas de turnos por estado con filtros de fecha
            $estadisticasTurnos = $query->select('estado', DB::raw('count(*) as cantidad'))
                ->groupBy('estado')
                ->get()
                ->keyBy('estado');

            // Formatear las estadísticas según los estados solicitados
            $estadisticas = [
                'turnosAtendidos' => $estadisticasTurnos->get(EstadoTurno::ATENDIDO->value)?->cantidad ?? 0,
                'turnosCancelados' => $estadisticasTurnos->get(EstadoTurno::CANCELADO->value)?->cantidad ?? 0,
                'turnosRechazados' => $estadisticasTurnos->get(EstadoTurno::RECHAZADO->value)?->cantidad ?? 0,
                'turnosReprogramados' => $this->contarTurnosReprogramadosConFechas($doctorId, $fechaInicio, $fechaFin),
                'turnosAceptados' => $estadisticasTurnos->get(EstadoTurno::ACEPTADO->value)?->cantidad ?? 0,
                'turnosDesaprovechados' => $estadisticasTurnos->get(EstadoTurno::DESAPROVECHADO->value)?->cantidad ?? 0,
            ];

            // Calcular ausencias en el rango de fechas
            $ausencias = $this->calcularAusenciasConFechas($doctorId, $fechaInicio, $fechaFin);

            return response()->json([
                'doctor_id' => $doctorId,
                'doctor' => [
                    'id' => $doctor->doctor_id,
                    'nombre' => $doctor->nombre_apellido ?? null,
                    'especialidad' => $doctor->especialidad->nombre ?? null
                ],
                'periodo' => [
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin
                ],
                'estadisticas' => $estadisticas,
                'ausencias' => $ausencias
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener estadísticas del doctor con fechas',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Contar turnos reprogramados para un doctor con filtros de fecha
     *
     * @param int $doctorId
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @return int
     */
    private function contarTurnosReprogramadosConFechas($doctorId, $fechaInicio = null, $fechaFin = null): int
    {
        $query = Turno::where('doctor_id', $doctorId)
            ->where('reprogramado', true);

        if ($fechaInicio) {
            $query->where('fecha', '>=', $fechaInicio);
        }
        if ($fechaFin) {
            $query->where('fecha', '<=', $fechaFin);
        }

        return $query->count();
    }

    /**
     * Calcular ausencias para un doctor con filtros de fecha
     *
     * @param int $doctorId
     * @param string|null $fechaInicio
     * @param string|null $fechaFin
     * @return int
     */
    private function calcularAusenciasConFechas($doctorId, $fechaInicio = null, $fechaFin = null): int
    {
        try {
            // Query base para horarios disponibles
            $queryHorarios = HorarioDisponible::where('doctor_id', $doctorId)
                ->whereNotNull('fecha');

            // Query base para turnos
            $queryTurnos = Turno::where('doctor_id', $doctorId)
                ->whereNotNull('fecha');

            // Aplicar filtros de fecha si se proporcionan
            if ($fechaInicio) {
                $queryHorarios->where('fecha', '>=', $fechaInicio);
                $queryTurnos->where('fecha', '>=', $fechaInicio);
            }
            if ($fechaFin) {
                $queryHorarios->where('fecha', '<=', $fechaFin);
                $queryTurnos->where('fecha', '<=', $fechaFin);
            }

            // Obtener fechas únicas
            $fechasConHorarios = $queryHorarios->select('fecha')
                ->distinct()
                ->pluck('fecha')
                ->toArray();

            $fechasConTurnos = $queryTurnos->select('fecha')
                ->distinct()
                ->pluck('fecha')
                ->toArray();

            // Contar días con horarios disponibles pero sin turnos
            $ausencias = 0;
            foreach ($fechasConHorarios as $fecha) {
                if (!in_array($fecha, $fechasConTurnos)) {
                    $ausencias++;
                }
            }

            return $ausencias;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
