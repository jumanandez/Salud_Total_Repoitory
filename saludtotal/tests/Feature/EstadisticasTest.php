<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Doctor;
use App\Models\Turno;
use App\Models\HorarioDisponible;
use App\Models\Especialidad;
use App\Enum\EstadoTurno;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstadisticasTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear datos de prueba
        $this->crearDatosDePrueba();
    }

    private function crearDatosDePrueba()
    {
        // Crear especialidad
        $especialidad = Especialidad::create([
            'nombre' => 'Cardiología',
            // Agregar otros campos necesarios según tu migración
        ]);

        // Crear doctor
        $this->doctor = Doctor::create([
            'nombre' => 'Dr. Test',
            'especialidad' => $especialidad->especialidad_id,
            // Agregar otros campos necesarios según tu migración
        ]);

        // Crear algunos turnos de prueba
        Turno::create([
            'doctor_id' => $this->doctor->doctor_id,
            'paciente_id' => 1,
            'fecha' => '2025-01-15',
            'hora' => '10:00:00',
            'estado' => EstadoTurno::ATENDIDO,
        ]);

        Turno::create([
            'doctor_id' => $this->doctor->doctor_id,
            'paciente_id' => 2,
            'fecha' => '2025-01-16',
            'hora' => '11:00:00',
            'estado' => EstadoTurno::CANCELADO,
        ]);

        Turno::create([
            'doctor_id' => $this->doctor->doctor_id,
            'paciente_id' => 3,
            'fecha' => '2025-01-17',
            'hora' => '09:00:00',
            'estado' => EstadoTurno::ACEPTADO,
            'reprogramado' => true,
        ]);
    }

    public function test_puede_obtener_estadisticas_por_doctor()
    {
        $response = $this->getJson("/api/estadisticas/doctor/{$this->doctor->doctor_id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'doctor_id',
                    'doctor' => [
                        'id',
                        'nombre',
                        'especialidad'
                    ],
                    'estadisticas' => [
                        'turnosAtendidos',
                        'turnosCancelados',
                        'turnosRechazados',
                        'turnosReprogramados',
                        'turnosAceptados',
                        'turnosDesaprovechados'
                    ],
                    'ausencias'
                ]);

        // Verificar valores específicos
        $data = $response->json();
        $this->assertEquals(1, $data['estadisticas']['turnosAtendidos']);
        $this->assertEquals(1, $data['estadisticas']['turnosCancelados']);
        $this->assertEquals(1, $data['estadisticas']['turnosAceptados']);
        $this->assertEquals(1, $data['estadisticas']['turnosReprogramados']);
    }

    public function test_puede_obtener_estadisticas_globales()
    {
        $response = $this->getJson('/api/estadisticas/globales');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'estadisticas' => [
                        'turnosAtendidos',
                        'turnosCancelados',
                        'turnosRechazados',
                        'turnosReprogramados',
                        'turnosAceptados',
                        'turnosDesaprovechados'
                    ],
                    'ausenciasGlobales',
                    'totalDoctores'
                ]);
    }

    public function test_puede_obtener_estadisticas_de_todos_los_doctores()
    {
        $response = $this->getJson('/api/estadisticas/doctores');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'doctores' => [
                        '*' => [
                            'doctor_id',
                            'doctor' => [
                                'id',
                                'nombre',
                                'especialidad'
                            ],
                            'estadisticas' => [
                                'turnosAtendidos',
                                'turnosCancelados',
                                'turnosRechazados',
                                'turnosReprogramados',
                                'turnosAceptados',
                                'turnosDesaprovechados'
                            ],
                            'ausencias'
                        ]
                    ]
                ]);
    }

    public function test_puede_obtener_estadisticas_por_doctor_con_fechas()
    {
        $response = $this->getJson("/api/estadisticas/doctor/{$this->doctor->doctor_id}/fechas?fecha_inicio=2025-01-01&fecha_fin=2025-01-31");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'doctor_id',
                    'doctor' => [
                        'id',
                        'nombre',
                        'especialidad'
                    ],
                    'periodo' => [
                        'fecha_inicio',
                        'fecha_fin'
                    ],
                    'estadisticas' => [
                        'turnosAtendidos',
                        'turnosCancelados',
                        'turnosRechazados',
                        'turnosReprogramados',
                        'turnosAceptados',
                        'turnosDesaprovechados'
                    ],
                    'ausencias'
                ]);
    }

    public function test_devuelve_error_404_para_doctor_inexistente()
    {
        $response = $this->getJson('/api/estadisticas/doctor/99999');

        $response->assertStatus(404)
                ->assertJson([
                    'error' => 'Doctor no encontrado'
                ]);
    }
}
