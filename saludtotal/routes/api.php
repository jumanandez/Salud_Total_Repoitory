<?php

use App\Http\Controllers\ApiTurnoController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\ProfesionalesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes para Aplicación de Escritorio
|--------------------------------------------------------------------------
|
| Estas rutas están destinadas específicamente para la aplicación de escritorio
| que maneja la creación de turnos. No requieren autenticación web ya que
| se asume que la aplicación de escritorio manejará su propia autenticación.
|
*/

// Rutas para manejo de pacientes
Route::prefix('pacientes')->group(function () {
    Route::get('/', [ApiTurnoController::class, 'listarPacientes']);
    Route::get('/buscar', [ApiTurnoController::class, 'buscarPacientes']);
    Route::get('/{id}', [ApiTurnoController::class, 'obtenerPaciente']);
});

// Rutas para manejo de turnos
Route::prefix('turnos')->group(function () {
    Route::post('/', [ApiTurnoController::class, 'crearTurno']);
    Route::get('/especialidades', [ApiTurnoController::class, 'especialidadesWithDoctores']);
    Route::get('/disponibles', [TurnoController::class, 'turnosDisponibles']);

});

// Rutas para profesionales (reutilizando el controlador existente)
Route::prefix('profesionales')->group(function () {
    Route::get('/especialidades', [ProfesionalesController::class, 'especialidades']);
    Route::get('/especialidades/{especialidad_id}/doctores', [ProfesionalesController::class, 'doctoresByEspecialidad']);
    Route::get('/doctores', [ProfesionalesController::class, 'doctores']);
    Route::get('/{doctor_id}/horarios', [ProfesionalesController::class, 'horarios']);
    Route::get('/doctor/{doctor_id}', [ProfesionalesController::class, 'nombreDoctorById']);
});
