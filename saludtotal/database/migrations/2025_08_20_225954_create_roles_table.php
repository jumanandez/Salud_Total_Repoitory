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
        Schema::create('roles', function (Blueprint $table) {
            $table->biginteger('id_rol')->autoIncrement();
            $table->string('name')->unique();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
        Schema::create('usuario_rol', function (Blueprint $table) {
            $table->biginteger('id_usuario');
            $table->biginteger('id_rol');
            $table->foreign('id_usuario')->references('usuario_id')->on('usuarios')->onDelete('cascade');
            $table->foreign('id_rol')->references('id_rol')->on('roles')->onDelete('cascade');
            $table->primary(['id_usuario', 'id_rol']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
        Schema::dropIfExists('usuario_rol');
    }
};
