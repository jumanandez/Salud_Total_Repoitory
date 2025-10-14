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
        Schema::table('tiempo_consulta', function (Blueprint $table) {
            // Elimina la FK anterior si existe
            //$table->dropForeign('tiempo_consulta_doctor_id_foreign');
            // Agrega la nueva FK hacia usuarios
            $table->foreign('doctor_id')->references('usuario_id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tiempo_consulta', function (Blueprint $table) {
            $table->dropForeign('tiempo_consulta_doctor_id_foreign');
            // Si antes referenciaba a doctores, puedes restaurar la FK original aquí
            // $table->foreign('doctor_id')->references('doctor_id')->on('doctores')->onDelete('cascade');
        });
    }
};
