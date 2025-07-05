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
            // $table->dropColumn('fecha_reprogramacion');
            // $table->dropColumn('fecha_solicitud_reprogramacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            $table->dateTime('fecha_reprogramacion')->nullable();
            $table->dateTime('fecha_solicitud_cancelacion')->nullable();
        });
    }
};
