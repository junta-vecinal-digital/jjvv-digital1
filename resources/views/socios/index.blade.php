<x-admin-layout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Libro de Socios (RO-01)</h2>
            <p class="text-sm text-slate-500">Gestión de registro y validación de firma presencial (Ley 19.418)</p>
        </div>
        <a href="{{ route('socios.create') }}" class="px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Registrar Socio Interno
        </a>
    </div>

    <!-- Mensajes de Estado -->
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700 border border-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filtros de Estado -->
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="{{ route('socios.index') }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg border {{ request('estado') === null ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
            Todos
        </a>
        <a href="{{ route('socios.index', ['estado' => 'pendiente_firma']) }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg border {{ request('estado') === 'pendiente_firma' ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
            Pendientes de Firma
        </a>
        <a href="{{ route('socios.index', ['estado' => 'activo']) }}" 
           class="px-4 py-2 text-sm font-medium rounded-lg border {{ request('estado') === 'activo' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
            Activos
        </a>
    </div>

    <!-- Tabla de Socios con Documentos y Cita Agendada -->
    <div class="rounded-lg border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Socio / RUT</th>
                        <th class="px-6 py-4">Contacto & Dirección</th>
                        <th class="px-6 py-4">Documentos Adjuntos</th>
                        <th class="px-6 py-4">Cita Agendada</th>
                        <th class="px-6 py-4">Estado Legal</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($socios as $socio)
                        <tr class="hover:bg-slate-50">
                            <!-- Socio y RUT -->
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $socio->nombre_completo }}</div>
                                <div class="text-xs text-slate-400">RUT: {{ $socio->rut }}</div>
                            </td>

                            <!-- Contacto y Dirección -->
                            <td class="px-6 py-4">
                                <div>{{ $socio->direccion }}</div>
                                <div class="text-xs text-slate-400">{{ $socio->telefono }}</div>
                            </td>

                            <!-- Documentos Subidos (Cédula y Comprobante) -->
                            <td class="px-6 py-4 text-xs space-y-1">
                                @if($socio->ruta_cedula)
                                    <a href="{{ asset('storage/' . $socio->ruta_cedula) }}" target="_blank" class="block text-indigo-600 font-medium hover:underline">
                                        📄 Ver Cédula
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Sin Cédula</span>
                                @endif

                                @if($socio->ruta_comprobante_domicilio)
                                    <a href="{{ asset('storage/' . $socio->ruta_comprobante_domicilio) }}" target="_blank" class="block text-indigo-600 font-medium hover:underline">
                                        🏠 Ver Comprobante
                                    </a>
                                @else
                                    <span class="text-slate-400 italic">Sin Comprobante</span>
                                @endif
                            </td>

                            <!-- Fecha de Cita Agendada -->
                            <td class="px-6 py-4 text-xs">
                                @if($socio->fecha_cita_firma)
                                    <div class="font-bold text-slate-800">{{ $socio->fecha_cita_firma->format('d/m/Y H:i') }} hrs</div>
                                    @if($socio->observaciones_cita)
                                        <div class="text-slate-400 italic text-[11px]">{{ Str::limit($socio->observaciones_cita, 30) }}</div>
                                    @endif
                                @else
                                    <span class="text-slate-400 italic">Sin Cita</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-xs">
                                @if($socio->cita)
                                    <div class="font-bold text-indigo-600">
                                        {{ $socio->cita->fecha_hora_inicio->format('d/m/Y H:i') }} hrs
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        {{ $socio->cita->fecha_hora_inicio->diffForHumans() }}
                                    </div>
                                @elseif($socio->estado === 'por_firmar' || $socio->estado === 'pendiente_firma')
                                    <span class="text-amber-600 font-medium italic">Sin agendar</span>
                                @else
                                    <span class="text-slate-400 italic">-</span>
                                @endif
                            </td>

                            <!-- Estado Legal -->
                            <td class="px-6 py-4">
                                @if($socio->estado === 'pendiente_firma')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pendiente Firma
                                    </span>
                                @elseif($socio->estado === 'activo')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Socio Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                        {{ ucfirst($socio->estado) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Acciones (Boton Confirmar Firma) -->
                            <td class="px-6 py-4 text-right">
                                @if($socio->estado === 'pendiente_firma')
                                    <form method="POST" action="{{ route('socios.confirmar-firma', $socio) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                onclick="return confirm('¿Confirmas que el vecino acudió a la sede y firmó el Libro de Registro físico?')"
                                                class="px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-md transition shadow-sm">
                                            Confirmar Firma
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs text-slate-400">Firmado el {{ $socio->fecha_firma_libro?->format('d/m/Y') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-400">
                                No se encontraron registros de socios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        <div class="px-6 py-4 border-t border-slate-200">
            {{ $socios->links() }}
        </div>
    </div>
</x-admin-layout>