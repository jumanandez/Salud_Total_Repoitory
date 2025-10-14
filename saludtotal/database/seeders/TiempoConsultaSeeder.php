<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiempoConsultaSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener todos los doctores antiguos
        $doctores = DB::table('doctores')->get();

        foreach ($doctores as $doctor) {
            // Buscar el usuario correspondiente por email
            $usuario = DB::table('usuarios')->where('email', $doctor->email)->first();
            if ($usuario) {
                // Actualizar tiempo_consulta: poner usuario_id en el campo doctor_id
                DB::table('tiempo_consulta')
                    ->where('doctor_id', $doctor->doctor_id)
                    ->update(['doctor_id' => $usuario->usuario_id]);
            }
        }
    }
}
