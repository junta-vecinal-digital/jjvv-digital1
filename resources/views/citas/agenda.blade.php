<x-admin-layout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Agenda de Citas Presenciales</h2>
            <p class="text-sm text-slate-500">Genera bloques de atención y gestiona las reservas del libro de socios.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Formulario Generador de Bloques -->
        <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Generar Horarios en Lote</h3>
            
            <form action="{{ route('citas.generar') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha *</label>
                    <input type="date" name="fecha" value="{{ old('fecha', $fecha) }}" min="{{ date('Y-m-d') }}" max="{{ now()->addMonths(6)->format('Y-m-d') }}" required class="w-full rounded-lg border-slate-200 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Hora Inicio *</label>
                        <input type="time" name="hora_inicio" value="{{ old('hora_inicio', '18:00') }}" required class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Hora Fin *</label>
                        <input type="time" name="hora_fin" value="{{ old('hora_fin', '20:00') }}" required class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Duración por Cita *</label>
                    <select name="duracion" required class="w-full rounded-lg border-slate-200 text-sm">
                        <option value="15">15 Minutos</option>
                        <option value="20">20 Minutos</option>
                        <option value="30" selected>30 Minutos</option>
                        <option value="45">45 Minutos</option>
                        <option value="60">60 Minutos</option>
                    </select>
                </div>

                <button type="submit" class="w-full py-2.5 px-4 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition">
                    Generar Bloques
                </button>
            </form>
        </div>

        <!-- Listado por Día -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-base">Citas para el {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</h3>
                
                <form method="GET" action="{{ route('citas.agenda') }}" class="flex items-center gap-2">
                    <input type="date" name="fecha" value="{{ $fecha }}" class="rounded-lg border-slate-200 text-sm py-1">
                    <button type="submit" class="px-3 py-1 bg-slate-800 text-white text-xs font-semibold rounded-lg">Ver Día</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">Horario</th>
                            <th class="px-6 py-3">Socio / Estado</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($citasDelDia as $cita)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $cita->fecha_hora_inicio->format('H:i') }} - {{ $cita->fecha_hora_fin->format('H:i') }} hrs
                                </td>

                                <td class="px-6 py-4">
                                    @if($cita->socio)
                                        <div class="font-bold text-indigo-600">
                                            <a href="{{ route('socios.show', $cita->socio) }}" class="hover:underline">
                                                {{ $cita->socio->nombre_completo }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-slate-400">RUT: {{ $cita->socio->rut }}</div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                            Disponible
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right space-x-2">
                                    @if($cita->socio)
                                        <form method="POST" action="{{ route('citas.liberar', $cita) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('¿Liberar esta hora de la agenda?')" class="px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 rounded border border-amber-200">
                                                Liberar
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('citas.destroy', $cita) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('¿Eliminar este bloque libre?')" class="px-2.5 py-1 text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 rounded border border-red-200">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-400">
                                    No hay horarios agendados para este día.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-admin-layout>