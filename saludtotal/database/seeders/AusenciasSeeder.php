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
        $baseDate = Carbon::create(2025, 12, 1);
        for ($i = 0; $i < 30; $i++) {
            $inicio = $baseDate->copy()->addDays($i * 2);
            $fin = $inicio->copy()->addDay();
            AusenciasDoctor::create([
                'doctor_id' => Doctor::first()->doctor_id + ($i % 5),
                'fecha_inicio' => $inicio->toDateString(),
                'fecha_fin' => $fin->toDateString(),
                'motivo_id' => 1
            ]);
        }
    }
}
