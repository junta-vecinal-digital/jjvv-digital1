<?php

namespace App\Http\Controllers;

use App\Models\HorarioAtencion;
use Illuminate\Http\Request;

class HorarioAtencionController extends Controller
{
    /**
     * Muestra el listado de bloques de atención y el formulario para crear un nuevo bloque.
     */
    public function index()
    {
        $horarios = HorarioAtencion::latest('fecha_hora_inicio')->paginate(10);

        return view('horarios.index', compact('horarios'));
    }

    /**
     * Almacena un nuevo bloque de horario de atención en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fecha_hora_inicio' => 'required|date|after:now',
            'fecha_hora_fin' => 'required|date|after:fecha_hora_inicio',
            'cupos_maximos' => 'required|integer|min:1|max:50',
        ]);

        HorarioAtencion::create([
            'fecha_hora_inicio' => $validated['fecha_hora_inicio'],
            'fecha_hora_fin' => $validated['fecha_hora_fin'],
            'cupos_maximos' => $validated['cupos_maximos'],
            'activo' => true,
        ]);

        return redirect()->route('horarios.index')
            ->with('success', '¡Bloque de atención creado exitosamente!');
    }

    /**
     * Cambia el estado (activo/inactivo) de un bloque de atención.
     */
    public function toggleEstado(HorarioAtencion $horario)
    {
        $horario->update([
            'activo' => !$horario->activo,
        ]);

        $estado = $horario->activo ? 'activado' : 'desactivado';

        return redirect()->back()
            ->with('success', "El bloque de atención ha sido {$estado}.");
    }

    /**
     * Elimina un bloque de atención si no tiene cupos reservados asociados.
     */
    public function destroy(HorarioAtencion $horario)
    {
        if ($horario->cupos_reservados > 0) {
            return redirect()->back()
                ->with('error', 'No se puede eliminar un bloque que ya tiene citas agendadas por vecinos.');
        }

        $horario->delete();

        return redirect()->route('horarios.index')
            ->with('success', 'Bloque de atención eliminado correctamente.');
    }
}