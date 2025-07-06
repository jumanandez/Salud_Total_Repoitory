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
        Schema::create('ausencias_doctores', function (Blueprint $table) {
            $table->bigInteger('ausencia_id')->autoIncrement();
            $table->bigInteger('doctor_id');
            $table->foreign('doctor_id')->references('doctor_id')->on('doctores')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->bigInteger('motivo_id');
            $table->foreign('motivo_id')->references('motivo_id')->on('motivos_de_ausencia');
            $table->text('detalle')->max(500)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ausencias_doctores');
    }
};
