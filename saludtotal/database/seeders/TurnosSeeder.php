<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Turno;
use App\Models\User;
use App\Models\Doctor;
use App\Enum\EstadoTurno;
use Carbon\Carbon;

class TurnosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            EstadoTurno::ATENDIDO,
            EstadoTurno::PENDIENTE,
            EstadoTurno::CANCELADO,
            EstadoTurno::RECHAZADO,
            EstadoTurno::DESAPROVECHADO
        ];
        $meses = [2,3,4,5,6,7,8]; // Abril, Junio, Julio, Agosto
        $anios = [2025];
        $pacientes = User::pluck('paciente_id')->toArray();
        $doctores = Doctor::pluck('doctor_id')->toArray();
        $faker = \Faker\Factory::create('es_ES');
        $turnosPorMes = 25;
        foreach ($anios as $anio) {
            foreach ($meses as $mes) {
                for ($i = 0; $i < $turnosPorMes; $i++) {
                    $dia = $faker->numberBetween(1, 28);
                    $fecha = Carbon::create($anio, $mes, $dia)->toDateString();
                    Turno::create([
                        'doctor_id' => $faker->randomElement($doctores),
                        'paciente_id' => $faker->randomElement($pacientes),
                        'fecha' => $fecha,
                        'hora' => $faker->time('H:i'),
                        'estado' => $faker->randomElement($estados),
                    ]);
                }
            }
        }
    }
}
