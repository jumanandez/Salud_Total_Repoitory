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
        Schema::table('turnos', function (Blueprint $table) {
            $table->dateTime('fecha_edicion')->nullable();
            $table->dateTime('fecha_cancelacion')->nullable();
            $table->boolean('solicita_reprogramacion')->nullable();
            $table->boolean('reprogramado')->nullable();
            $table->biginteger('reprogramado_por')->nullable();
            $table->boolean('solicita_cancelacion')->nullable();
            $table->dateTime('fecha_solicitud_cancelacion')->nullable();
            $table->biginteger('cancelado_por')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropColumn('fecha_edicion')->nullable();
            $table->dropColumn('fecha_cancelacion')->nullable();
            $table->dropColumn('solicita_reprogramacion')->nullable();
            $table->dropColumn('reprogramado')->nullable();
            $table->dropColumn('reprogramado_por')->nullable();
            $table->dropColumn('solicita_cancelacion')->nullable();
            $table->dropColumn('fecha_solicitud_cancelacion')->nullable();
            $table->dropColumn('cancelado_por')->nullable();
        });
    }
};
