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
use App\Models\AusenciasDoctor;
use App\Models\User;
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
    public function estadisticasGlobales(Request $request): JsonResponse
    {
        try {
            // Filtros de fecha
            $desde = $request->input('desde');
            $hasta = $request->input('hasta');

            // Query base para turnos
            $turnosTotales = Turno::
            where(function($query) use ($desde, $hasta) {
                if ($desde) {
                    $query->where('fecha', '>=', $desde);
                }
                if ($hasta) {
                    $query->where('fecha', '<=', $hasta);
                }
            })->count();

            $turnosQuery = function($estado) use ($desde, $hasta) {
                $query = Turno::where('estado', $estado);
                if ($desde) {
                    $query->where('fecha', '>=', $desde);
                }
                if ($hasta) {
                    $query->where('fecha', '<=', $hasta);
                }
                return $query->count();
            };

            $turnosAtendidos = $turnosQuery(EstadoTurno::ATENDIDO->value);
            $turnosCancelados = $turnosQuery(EstadoTurno::CANCELADO->value);
            $turnosRechazados = $turnosQuery(EstadoTurno::RECHAZADO->value);
            $turnosAceptados = $turnosQuery(EstadoTurno::ACEPTADO->value);
            $turnosDesaprovechados = $turnosQuery(EstadoTurno::DESAPROVECHADO->value);

            // Turnos reprogramados
            $turnosReprogramadosQuery = Turno::where('reprogramado', true);
            if ($desde) {
                $turnosReprogramadosQuery->where('fecha', '>=', $desde);
            }
            if ($hasta) {
                $turnosReprogramadosQuery->where('fecha', '<=', $hasta);
            }
            $turnosReprogramados = $turnosReprogramadosQuery->count();

            // Calcular ausencias globales (opcional: podrías filtrar por fecha si tu modelo lo permite)
            $ausenciasGlobales = AusenciasDoctor::when($desde, function($q) use ($desde) {
                    $q->where('fecha_inicio', '>=', $desde);
                })
                ->when($hasta, function($q) use ($hasta) {
                    $q->where('fecha_fin', '<=', $hasta);
                })
                ->count();

            // Total de doctores
            $totalDoctores = Doctor::count();
            $totalUsers = User::count();
            return response()->json([
                'totalTurnos' => $turnosTotales,
                'turnosAtendidos' => $turnosAtendidos,
                'turnosCancelados' => $turnosCancelados,
                'turnosRechazados' => $turnosRechazados,
                'turnosAceptados' => $turnosAceptados,
                'turnosDesaprovechados' => $turnosDesaprovechados,
                'turnosReprogramados' => $turnosReprogramados,
                'ausenciasGlobales' => $ausenciasGlobales,
                'totalDoctores' => $totalDoctores,
                'totalPacientes' => $totalUsers,
                'desde' => $desde,
                'hasta' => $hasta
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
