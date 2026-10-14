<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Socio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    /**
     * Vista del vecino: Muestra su hora reservada o las horas disponibles.
     */
    public function index()
    {
        $user = Auth::user();
        $socio = Socio::where('user_id', $user->id)->first();

        $citaReservada = null;
        $citasDisponibles = collect();

        if ($socio) {
            $citaReservada = Cita::where('socio_id', $socio->id)
                ->where('fecha_hora_inicio', '>=', now())
                ->first();

            if ($socio->estado === 'por_firmar' && !$citaReservada) {
                // Horas disponibles en los próximos 30 días
                $citasDisponibles = Cita::whereNull('socio_id')
                    ->where('fecha_hora_inicio', '>=', now())
                    ->where('fecha_hora_inicio', '<=', now()->addDays(30))
                    ->orderBy('fecha_hora_inicio')
                    ->get()
                    ->groupBy(function ($cita) {
                        return $cita->fecha_hora_inicio->format('Y-m-d');
                    });
            }
        }

        return view('citas.index', compact('socio', 'citaReservada', 'citasDisponibles'));
    }

    /**
     * Reserva atómica de una cita por parte del vecino.
     */
    public function reservar(Request $request, Cita $cita)
    {
        $user = Auth::user();
        $socio = Socio::where('user_id', $user->id)->firstOrFail();

        // Regla 1: Solo socios 'por_firmar'
        if ($socio->estado !== 'por_firmar') {
            return redirect()->back()->with('error', 'Solo los socios en estado por firmar pueden reservar hora.');
        }

        // Regla 2: Solo una cita activa a la vez
        $citaExistente = Cita::where('socio_id', $socio->id)
            ->where('fecha_hora_inicio', '>=', now())
            ->exists();

        if ($citaExistente) {
            return redirect()->back()->with('error', 'Ya tienes una hora reservada. Debes cancelarla antes de tomar otra.');
        }

        // Regla 3: Solo horas futuras dentro de los próximos 30 días
        if ($cita->fecha_hora_inicio->isPast() || $cita->fecha_hora_inicio->gt(now()->addDays(30))) {
            return redirect()->back()->with('error', 'Solo puedes reservar horas futuras dentro de los próximos 30 días.');
        }

        // REGLA DE ATOMICIDAD: UPDATE condicional en BD
        $affected = DB::table('citas')
            ->where('id', $cita->id)
            ->whereNull('socio_id')
            ->update([
                'socio_id' => $socio->id,
                'updated_at' => now(),
            ]);

        if ($affected === 0) {
            return redirect()->back()->with('error', 'Lo sentimos, esta hora acaba de ser tomada por otro vecino. Por favor elige otro horario.');
        }

        return redirect()->route('citas.index')->with('success', '¡Hora reservada con éxito!');
    }

    /**
     * Cancelar reserva por parte del vecino.
     */
    public function cancelar(Cita $cita)
    {
        $user = Auth::user();
        $socio = Socio::where('user_id', $user->id)->firstOrFail();

        if ($cita->socio_id !== $socio->id) {
            return redirect()->back()->with('error', 'No tienes permiso para cancelar esta cita.');
        }

        if ($cita->fecha_hora_inicio->isPast()) {
            return redirect()->back()->with('error', 'No puedes cancelar una cita que ya transcurrió.');
        }

        $cita->update(['socio_id' => null]);

        return redirect()->route('citas.index')->with('success', 'Has cancelado tu reserva correctamente.');
    }

    /**
     * Vista de agenda para dirigentes (Generador y Listado por día).
     */
    public function agenda(Request $request)
    {
        $fecha = $request->get('fecha', now()->format('Y-m-d'));

        $citasDelDia = Cita::with('socio.user')
            ->whereDate('fecha_hora_inicio', $fecha)
            ->orderBy('fecha_hora_inicio')
            ->get();

        return view('citas.agenda', compact('citasDelDia', 'fecha'));
    }

    /**
     * Generar bloques de horarios en lote (Dirigentes).
     */
    public function generarBloques(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date|after_or_equal:today|before_or_equal:' . now()->addMonths(6)->format('Y-m-d'),
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'duracion' => 'required|integer|in:15,20,30,45,60',
        ]);

        $fechaStr = $validated['fecha'];
        $inicio = Carbon::parse("{$fechaStr} {$validated['hora_inicio']}");
        $fin = Carbon::parse("{$fechaStr} {$validated['hora_fin']}");
        $duracion = (int) $validated['duracion'];

        $creados = 0;
        $omitidos = 0;

        while ($inicio->copy()->addMinutes($duracion)->lte($fin)) {
            $bloqueFin = $inicio->copy()->addMinutes($duracion);

            // Evitar duplicados por restricción de unicidad
            $existe = Cita::where('fecha_hora_inicio', $inicio)->exists();

            if (!$existe) {
                Cita::create([
                    'fecha_hora_inicio' => $inicio,
                    'fecha_hora_fin' => $bloqueFin,
                    'socio_id' => null,
                ]);
                $creados++;
            } else {
                $omitidos++;
            }

            $inicio->addMinutes($duracion);
        }

        return redirect()->route('citas.agenda', ['fecha' => $fechaStr])
            ->with('success', "Se generaron {$creados} bloques correctamente." . ($omitidos > 0 ? " ({$omitidos} ya existían)." : ''));
    }

    /**
     * Liberar hora reservada (Dirigentes).
     */
    public function liberar(Cita $cita)
    {
        $cita->update(['socio_id' => null]);

        return redirect()->back()->with('success', 'La hora ha sido liberada correctamente.');
    }

    /**
     * Eliminar hora libre (Dirigentes). NUNCA eliminar reservadas.
     */
    public function destroy(Cita $cita)
    {
        if ($cita->socio_id !== null) {
            return redirect()->back()->with('error', 'No se puede eliminar una hora que ya está reservada por un vecino.');
        }

        $cita->delete();

        return redirect()->back()->with('success', 'La hora libre ha sido eliminada.');
    }
}