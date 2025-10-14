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
        Schema::create('doctor_especialidad', function (Blueprint $table) {
            $table->bigInteger('doctor_id');
            $table->bigInteger('especialidad_id');
            $table->primary(['doctor_id', 'especialidad_id']);
            $table->foreign('doctor_id')->references('usuario_id')->on('usuarios')->onDelete('cascade');
            $table->foreign('especialidad_id')->references('especialidad_id')->on('especialidades')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_especialidad');
    }
};
