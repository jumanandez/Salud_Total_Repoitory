<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfesionalesController;
use App\Http\Controllers\TurnoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profesionales', [ProfesionalesController::class, 'index'])->name('profesionales.index');
Route::get('profesionales/especialidades', [ProfesionalesController::class, 'especialidades'])->name('profesionales.especialidades');
Route::get('profesionales/especialidades/{especialidad_id}/doctores', [ProfesionalesController::class, 'doctoresByEspecialidad'])->name('especialidad.doctores');
Route::get('profesionales/doctores', [ProfesionalesController::class, 'doctores'])->name('profesionales.doctores');
Route::get('profesionales/{doctor_id}/horarios', [ProfesionalesController::class, 'horarios'])->name('profesionales.horarios');
Route::get('profesionales/doctor/{doctor_id}', [ProfesionalesController::class, 'nombreDoctorById'])->name('profesional.nombre');
Route::get('turnos', [TurnoController::class, 'index'])->name('turnos.index');
Route::get('turnos/doctor/disponibles', [TurnoController::class, 'turnosDisponibles'])->name('turnos.disponibles');


Route::middleware('auth')->group(function () {
    Route::get('turnos/create', [TurnoController::class, 'create'])->name('turnos.create');
    Route::post('turnos/store', [TurnoController::class, 'store'])->name('turnos.store');

    Route::get('/turnos/mis-turnos', [TurnoController::class, 'misTurnos'])->name('mis-turnos');

    Route::get('turnos/mis-turnos/{turno_id}',
        [TurnoController::class, 'turnoDetails'])->name('turnos.details');

    Route::put('/turnos/mis-turnos/solicitar-cancelacion/{turno_id}',
        [TurnoController::class, 'solicitarCancelacion'])->name('mis-turnos.solicitar-cancelacion');

    Route::post('/turnos/mis-turnos/solicitar-reprogramacion',
        [TurnoController::class, 'solicitarReprogramacion'])->name('mis-turnos.solicitar-reprogramacion');

    Route::delete('/turnos/cancelar/{turno}',
        [TurnoController::class, 'cancelar'])->name('turnos.cancelar');

    Route::put('/turnos/reprogramar/{turno}',
        [TurnoController::class, 'reprogramar'])->name('turnos.reprogramar');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
