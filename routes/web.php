```php
<?php

use App\Http\Controllers\PortalVecinoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\HorarioAtencionController;
use App\Http\Controllers\CitaController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Models\Certificado;
use App\Models\Socio;

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Rutas para usuarios autenticados y verificados
Route::middleware(['auth', 'verified'])->group(function () {

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Portal del vecino
    Route::get('/portal', [PortalVecinoController::class, 'index'])
        ->name('portal.index');

    Route::post('/portal/pre-registro', [PortalVecinoController::class, 'storePreRegistro'])
        ->name('portal.store');

    // Citas para vecinos
    Route::get('/citas', [CitaController::class, 'index'])
        ->name('citas.index');

    Route::post('/citas/{cita}/reservar', [CitaController::class, 'reservar'])
        ->name('citas.reservar');

    Route::delete('/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])
        ->name('citas.cancelar');

    // Panel de administración: directiva y administradores
    Route::middleware([
        CheckRole::class . ':admin,presidente,secretario,tesorero'
    ])->group(function () {

        // Dashboard con estadísticas
        Route::get('/dashboard', function () {
            $totalSocios = Socio::where('estado', 'activo')->count();

            $pendientesFirma = Socio::where('estado', 'pendiente_firma')->count();

            $totalCertificados = Certificado::whereMonth(
                'fecha_emision',
                now()->month
            )
                ->whereYear('fecha_emision', now()->year)
                ->count();

            return view('dashboard', compact(
                'totalSocios',
                'pendientesFirma',
                'totalCertificados'
            ));
        })->name('dashboard');

        // Módulo de socios
        Route::get('/socios', [SocioController::class, 'index'])
            ->name('socios.index');

        Route::get('/socios/crear', [SocioController::class, 'create'])
            ->name('socios.create');

        Route::post('/socios', [SocioController::class, 'store'])
            ->name('socios.store');

        Route::get('/socios/{socio}', [SocioController::class, 'show'])
            ->name('socios.show');

        Route::patch('/socios/{socio}/confirmar-firma', [SocioController::class, 'confirmarFirma'])
            ->name('socios.confirmar-firma');

        // Módulo de horarios de atención
        Route::get('/horarios', [HorarioAtencionController::class, 'index'])
            ->name('horarios.index');

        Route::post('/horarios', [HorarioAtencionController::class, 'store'])
            ->name('horarios.store');

        Route::patch('/horarios/{horario}/toggle', [HorarioAtencionController::class, 'toggleEstado'])
            ->name('horarios.toggle');

        Route::delete('/horarios/{horario}', [HorarioAtencionController::class, 'destroy'])
            ->name('horarios.destroy');

        // Agenda de citas: administración
        Route::get('/agenda', [CitaController::class, 'agenda'])
            ->name('citas.agenda');

        Route::post('/agenda/generar', [CitaController::class, 'generarBloques'])
            ->name('citas.generar');

        Route::patch('/agenda/{cita}/liberar', [CitaController::class, 'liberar'])
            ->name('citas.liberar');

        Route::delete('/agenda/{cita}', [CitaController::class, 'destroy'])
            ->name('citas.destroy');
    });
});

// Rutas de autenticación
require __DIR__.'/auth.php';
```