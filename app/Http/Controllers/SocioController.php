<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SocioController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->get('estado');

        $socios = Socio::with(['user', 'registradoPor'])
            ->when($estado, function ($query, $estado) {
                return $query->where('estado', $estado);
            })
            ->latest()
            ->paginate(10);

        return view('socios.index', compact('socios', 'estado'));
    }

    public function create()
    {
        return view('socios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rut' => 'required|string|unique:socios,rut',
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'meses_residencia' => 'required|integer|min:1',
            'cedula' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'comprobante_domicilio' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Carga de archivos si existen
        $rutaCedula = $request->hasFile('cedula') 
            ? $request->file('cedula')->store('documentos/cedulas', 'public') 
            : null;

        $rutaComprobante = $request->hasFile('comprobante_domicilio') 
            ? $request->file('comprobante_domicilio')->store('documentos/comprobantes', 'public') 
            : null;

        Socio::create([
            'rut' => $validated['rut'],
            'nombres' => $validated['nombres'],
            'apellidos' => $validated['apellidos'],
            'fecha_nacimiento' => $validated['fecha_nacimiento'],
            'direccion' => $validated['direccion'],
            'telefono' => $validated['telefono'],
            'meses_residencia' => $validated['meses_residencia'],
            'ruta_cedula' => $rutaCedula,
            'ruta_comprobante_domicilio' => $rutaComprobante,
            'estado' => 'pendiente_firma', // Estado inicial según Ley 19.418
            'registrado_por_user_id' => Auth::id(),
        ]);

        return redirect()->route('socios.index')
            ->with('success', 'Socio registrado correctamente. Queda pendiente la firma presencial en el libro físico.');
    }

    public function confirmarFirma(Socio $socio)
    {
        $socio->update([
            'estado' => 'activo',
            'fecha_firma_libro' => now(),
            'registrado_por_user_id' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', "Se ha confirmado la firma presencial del socio {$socio->nombre_completo}.");
    }
}