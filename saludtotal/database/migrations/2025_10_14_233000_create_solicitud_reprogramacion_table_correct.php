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
        Schema::create('solicitud_reprogramacion', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();
            $table->bigInteger('turno_id');
            $table->foreign('turno_id')->references('turno_id')->on('turnos')->onDelete('cascade');
            $table->date('fecha');
            $table->string('hora');
            $table->string('estado')->default('pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_reprogramacion');
    }
};
