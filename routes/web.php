<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\PortalVecinoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocioController;
use App\Http\Middleware\CheckRole;
use App\Models\Socio;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Rutas del Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para Vecinos
    Route::get('/portal', [PortalVecinoController::class, 'index'])->name('portal.index');
    Route::post('/portal/pre-registro', [PortalVecinoController::class, 'storePreRegistro'])->name('portal.store');
    
    Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
    Route::post('/citas/{cita}/reservar', [CitaController::class, 'reservar'])->name('citas.reservar');
    Route::delete('/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');

    // PANEL DE ADMINISTRACIÓN - Protegido para la Directiva
    Route::middleware([CheckRole::class . ':admin,presidente,secretario,tesorero'])->group(function () {
        
        Route::get('/dashboard', function () {
            $totalSocios = Socio::where('estado', 'activo')->count();
            $pendientesFirma = Socio::whereIn('estado', ['pendiente_firma', 'por_firmar'])->count();
            Route::get('/horarios', [HorarioAtencionController::class, 'index'])
                ->name('horarios.index');

            return view('dashboard', compact('totalSocios', 'pendientesFirma'));
        })->name('dashboard');

        // Módulo de Socios (Rutas completas)
        Route::get('/socios', [SocioController::class, 'index'])->name('socios.index');
        Route::get('/socios/crear', [SocioController::class, 'create'])->name('socios.create');
        Route::post('/socios', [SocioController::class, 'store'])->name('socios.store');
        Route::patch('/socios/{socio}/confirmar-firma', [SocioController::class, 'confirmarFirma'])->name('socios.confirmar-firma');

        // Agenda Dirigentes
        Route::get('/agenda', [CitaController::class, 'agenda'])->name('citas.agenda');
        Route::post('/agenda/generar', [CitaController::class, 'generarBloques'])->name('citas.generar');
        Route::patch('/agenda/{cita}/liberar', [CitaController::class, 'liberar'])->name('citas.liberar');
        Route::delete('/agenda/{cita}', [CitaController::class, 'destroy'])->name('citas.destroy');
    });
});

require __DIR__.'/auth.php';
