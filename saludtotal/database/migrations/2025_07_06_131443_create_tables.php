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
        Schema::create('doctores', function (Blueprint $table) {
            $table->bigInteger('doctor_id')->autoIncrement();
            $table->string('nombre_apellido');
            $table->string('email');
            $table->string('telefono');
            $table->bigInteger('especialidad_id');
            $table->foreign('especialidad_id')->references('especialidad_id')->on('especialidades')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctores');
    }
};
