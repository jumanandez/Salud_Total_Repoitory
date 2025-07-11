<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\AusenciasSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        $this->call([
            // Otros seeders
            // Ejemplo: EspecialidadesSeeder::class,
            // Ejemplo: DoctoresSeeder::class,
            AusenciasSeeder::class, // Asegúrate de que este Seeder esté definido
        ]);
    }
}
