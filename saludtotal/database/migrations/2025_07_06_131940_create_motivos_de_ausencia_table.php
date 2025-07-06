<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('motivos_de_ausencia', function (Blueprint $table) {
            $table->bigInteger('motivo_id')->autoIncrement();
            $table->string('nombre')->unique(); // Ej: 'vacaciones', 'licencia médica'
            $table->text('descripcion')->nullable(); // Opcional, para explicar el motivo
            $table->boolean('activo')->default(true); // Para desactivarlos sin borrarlos
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motivos_de_ausencia');
    }
};
