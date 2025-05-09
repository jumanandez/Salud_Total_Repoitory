<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfesionalesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/profesionales', [ProfesionalesController::class, 'index'])->name('profesionales.index');
Route::get('profesionales/especialidades', [ProfesionalesController::class, 'especialidades'])->name('profesionales.especialidades');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
