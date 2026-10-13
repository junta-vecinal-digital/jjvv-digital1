<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HorarioAtencion;

class PortalVecinoController extends Controller
{
    /**
     * Vista principal del portal del vecino.
     */
    public function index()
    {
        $user = Auth::user();
        $socio = Socio::where('user_id', $user->id)->first();

        $horariosDisponibles = HorarioAtencion::where('activo', true)
            ->where('fecha_hora_inicio', '>=', now())
            ->get()
            ->filter(fn($horario) => $horario->cupos_disponibles > 0);

        return view('portal.index', compact('socio', 'horariosDisponibles'));
    }
    

    /**
     * Guarda el pre-registro del vecino con archivos obligatorios y agendamiento.
     */
    public function storePreRegistro(Request $request)
    {
        $user = Auth::user();

        // Validar si el usuario ya envió su pre-registro
        if (Socio::where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Ya tienes una solicitud de inscripción en proceso.');
        }

        $validated = $request->validate([
            'rut' => 'required|string|unique:socios,rut',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'meses_residencia' => 'required|integer|min:1',
            // Adjuntos OBLIGATORIOS
            'cedula' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'comprobante_domicilio' => 'required|file|mimes:pdf,jpg,jpeg,png|max:4096',
            // Agendamiento de Cita Presencial
            'fecha_cita_firma' => 'required|date|after:now',
            'observaciones_cita' => 'nullable|string|max:500',
        ]);

        // Guardar archivos subidos
        $rutaCedula = $request->file('cedula')->store('documentos/cedulas', 'public');
        $rutaComprobante = $request->file('comprobante_domicilio')->store('documentos/comprobantes', 'public');

        // Crear la ficha de socio
        Socio::create([
            'user_id' => $user->id,
            'rut' => $validated['rut'],
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'direccion' => $validated['direccion'],
            'telefono' => $validated['telefono'],
            'meses_residencia' => $validated['meses_residencia'],
            'ruta_cedula' => $rutaCedula,
            'ruta_comprobante_domicilio' => $rutaComprobante,
            'estado' => 'pendiente_firma',
            'fecha_cita_firma' => $validated['fecha_cita_firma'],
            'observaciones_cita' => $validated['observaciones_cita'] ?? null,
        ]);

        return redirect()->route('portal.index')->with('success', '¡Pre-registro enviado exitosamente! Te esperamos en la sede en la fecha agendada para la firma presencial del libro.');
    }
}