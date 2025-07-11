<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\AusenciasDoctor;
use Carbon\Carbon;
class AusenciasSeeder extends Seeder
{

    public function run(): void
    {
        $meses = [4, 5, 6, 7]; // Abril a Julio
        $anio = 2025;
        $doctores = Doctor::pluck('doctor_id')->toArray();
        $motivoId = 1;
        $faker = \Faker\Factory::create('es_ES');
        foreach ($doctores as $doctorId) {
            foreach ($meses as $mes) {
                // Genera entre 1 y 3 ausencias por doctor por mes
                $ausenciasPorMes = $faker->numberBetween(1, 3);
                for ($i = 0; $i < $ausenciasPorMes; $i++) {
                    $diaInicio = $faker->numberBetween(1, 25);
                    $inicio = Carbon::create($anio, $mes, $diaInicio);
                    $fin = $inicio->copy()->addDays($faker->numberBetween(1, 3));
                    AusenciasDoctor::create([
                        'doctor_id' => $doctorId,
                        'fecha_inicio' => $inicio->toDateString(),
                        'fecha_fin' => $fin->toDateString(),
                        'motivo_id' => $motivoId
                    ]);
                }
            }
        }
    }
}
