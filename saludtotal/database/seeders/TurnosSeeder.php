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
        $meses = [2,3,4,5,6,7,8]; // Febrero a Agosto
        $anios = [2025];
        // Obtener todos los usuarios como pacientes
        $pacientes = \DB::table('usuarios')->pluck('usuario_id')->toArray();
        // Obtener usuarios que son doctores (tienen rol de doctor)
        $doctores = \DB::table('usuarios')
            ->join('usuario_rol', 'usuarios.usuario_id', '=', 'usuario_rol.id_usuario')
            ->join('roles', 'usuario_rol.id_rol', '=', 'roles.id_rol')
            ->where('roles.name', 'doctor')
            ->pluck('usuarios.usuario_id')
            ->toArray();
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
