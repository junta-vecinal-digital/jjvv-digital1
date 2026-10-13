<x-admin-layout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Horarios de Atención en Sede</h2>
            <p class="text-sm text-slate-500">Configura los bloques de horas disponibles para que los vecinos agenden su firma presencial.</p>
        </div>
    </div>

    <!-- Mensajes de Estado -->
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
        
        <!-- Formulario: Crear Nuevo Bloque -->
        <div class="lg:col-span-1 bg-white p-6 rounded-lg border border-slate-200 shadow-sm h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Nuevo Bloque de Atención</h3>
            
            <form action="{{ route('horarios.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Fecha y Hora de Inicio -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Inicio del Bloque *</label>
                    <input type="datetime-local" name="fecha_hora_inicio" value="{{ old('fecha_hora_inicio') }}" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('fecha_hora_inicio') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha y Hora de Fin -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Término del Bloque *</label>
                    <input type="datetime-local" name="fecha_hora_fin" value="{{ old('fecha_hora_fin') }}" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('fecha_hora_fin') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Cupos Máximos -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Cupos Máximos (Vecinos) *</label>
                    <input type="number" name="cupos_maximos" value="{{ old('cupos_maximos', 5) }}" min="1" max="50" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-slate-400 mt-1">Límite de citas simultáneas que aceptará el sistema.</p>
                    @error('cupos_maximos') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full py-2.5 px-4 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Guardar Bloque
                </button>
            </form>
        </div>

        <!-- Tabla: Listado de Bloques -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-200 bg-slate-50">
                <h3 class="font-bold text-slate-800 text-base">Bloques Programados</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">Fecha & Horario</th>
                            <th class="px-6 py-3">Cupos (Agendados / Max)</th>
                            <th class="px-6 py-3">Estado</th>
                            <th class="px-6 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($horarios as $horario)
                            <tr class="hover:bg-slate-50">
                                <!-- Fecha y Horario -->
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-800">
                                        {{ $horario->fecha_hora_inicio->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ $horario->fecha_hora_inicio->format('H:i') }} - {{ $horario->fecha_hora_fin->format('H:i') }} hrs
                                    </div>
                                </td>

                                <!-- Cupos -->
                                <td class="px-6 py-4 text-xs">
                                    <div class="font-semibold text-slate-700">
                                        {{ $horario->cupos_reservados }} / {{ $horario->cupos_maximos }}
                                    </div>
                                    <div class="text-slate-400">
                                        Disponibles: {{ $horario->cupos_disponibles }}
                                    </div>
                                </td>

                                <!-- Estado -->
                                <td class="px-6 py-4">
                                    @if($horario->activo)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                <!-- Acciones -->
                                <td class="px-6 py-4 text-right space-x-2">
                                    <!-- Alternar Estado -->
                                    <form method="POST" action="{{ route('horarios.toggle', $horario) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="px-2.5 py-1 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded border border-slate-300 transition">
                                            {{ $horario->activo ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>

                                    <!-- Eliminar (solo si no tiene reservaciones) -->
                                    @if($horario->cupos_reservados === 0)
                                        <form method="POST" action="{{ route('horarios.destroy', $horario) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('¿Seguro que deseas eliminar este bloque?')"
                                                    class="px-2.5 py-1 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded border border-red-200 transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                    No hay bloques de horario configurados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $horarios->links() }}
            </div>
        </div>

    </div>
</x-admin-layout>