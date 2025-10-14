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
        // Schema::create('turnos', function (Blueprint $table) {
        //     $table->bigInteger('turno_id')->autoIncrement();
        //     $table->bigInteger('paciente_id');
        //     $table->foreign('paciente_id')->references('paciente_id')->on('pacientes')->onDelete('cascade');
        //     $table->bigInteger('doctor_id');
        //     $table->foreign('doctor_id')->references('doctor_id')->on('doctores')->onDelete('cascade');
        //     $table->date('fecha');
        //     $table->string('hora');
        //     $table->enum('estado', ['activo', 'pendiente', 'cancelado', 'atendido', 'desaprovechado'])->default('activo');
        //     $table->string('motivo')->nullable();
        //     $table->text('observaciones')->nullable();
        //     $table->boolean('solicita_reprogramacion')->default(false);
        //     $table->boolean('reprogramado')->default(false);
        //     $table->biginteger('reprogramado_por')->nullable();
        //     $table->boolean('solicita_cancelacion')->default(false);
        //     $table->dateTime('fecha_solicitud_cancelacion')->nullable();
        //     $table->biginteger('cancelado_por')->nullable();
        //     $table->timestamps();
        //     $table->timestamp('canceled_at')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
