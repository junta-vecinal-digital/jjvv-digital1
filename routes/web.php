<?php

use App\Http\Controllers\PortalVecinoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\HorarioAtencionController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Models\Certificado;
use App\Models\Socio;


Route::get('/', function () {
    return view('welcome');
});

// Rutas de la comunidad / vecinos autenticados
Route::middleware(['auth', 'verified'])->group(function () {

    // Rutas del Perfil de Usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Portal público del vecino
    Route::get('/portal', [PortalVecinoController::class, 'index'])->name('portal.index');
    Route::post('/portal/pre-registro', [PortalVecinoController::class, 'storePreRegistro'])->name('portal.store');

    // PANEL DE ADMINISTRACIÓN - Protegido exclusivamente para Directiva / Admin
    Route::middleware([CheckRole::class . ':admin,presidente,secretario,tesorero'])->group(function () {
        Route::get('/dashboard', function () {
            $totalSocios = Socio::where('estado', 'activo')->count();
            $pendientesFirma = Socio::where('estado', 'pendiente_firma')->count();
            $totalCertificados = Certificado::whereMonth('fecha_emision', now()->month)
                ->whereYear('fecha_emision', now()->year)
                ->count();

            return view('dashboard', compact('totalSocios', 'pendientesFirma', 'totalCertificados'));
        })->name('dashboard');

        // Módulo de Socios
        Route::get('/socios', [SocioController::class, 'index'])->name('socios.index');
        Route::get('/socios/crear', [SocioController::class, 'create'])->name('socios.create');
        Route::post('/socios', [SocioController::class, 'store'])->name('socios.store');
        Route::patch('/socios/{socio}/confirmar-firma', [SocioController::class, 'confirmarFirma'])->name('socios.confirmar-firma');

        // Módulo de Horarios de Atención
        Route::get('/horarios', [HorarioAtencionController::class, 'index'])->name('horarios.index');
        Route::post('/horarios', [HorarioAtencionController::class, 'store'])->name('horarios.store');
        Route::patch('/horarios/{horario}/toggle', [HorarioAtencionController::class, 'toggleEstado'])->name('horarios.toggle');
        Route::delete('/horarios/{horario}', [HorarioAtencionController::class, 'destroy'])->name('horarios.destroy');
    });
});

require __DIR__.'/auth.php';