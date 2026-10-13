<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Portal del Vecino - Junta de Vecinos
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700 border border-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        @if($socio)
            <!-- Estado de la Solicitud del Vecino -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border border-slate-200">
                <h3 class="text-lg font-bold text-slate-800 mb-2">Estado de tu Registro de Socio</h3>

                @if($socio->estado === 'pendiente_firma')
                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-4 text-amber-800">
                        <p class="font-semibold text-base mb-1">⏳ Pre-registro Recibido (Pendiente Firma Presencial)</p>
                        <p class="text-sm">Tus antecedentes han sido subidos correctamente. Para completar tu incorporación formal según la <strong>Ley 19.418</strong>, debes acudir a la sede vecinal a firmar el Libro de Registro (RO-01).</p>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 space-y-2 text-sm">
                        <p><strong>Cita Agendada:</strong> <span class="text-indigo-600 font-bold">{{ $socio->fecha_cita_firma ? $socio->fecha_cita_firma->format('d/m/Y H:i hrs') : 'Sin agendar' }}</span></p>
                        <p><strong>Lugar:</strong> Sede Social de la Junta de Vecinos</p>
                        <p><strong>RUT:</strong> {{ $socio->rut }}</p>
                        <p><strong>Dirección:</strong> {{ $socio->direccion }}</p>
                    </div>

                @elseif($socio->estado === 'activo')
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-emerald-800">
                        <p class="font-semibold text-base mb-1">✅ Socio Activo</p>
                        <p class="text-sm">Tu firma presencial fue ratificada el <strong>{{ $socio->fecha_firma_libro->format('d/m/Y') }}</strong>. Ya eres socio oficial de la organización.</p>
                    </div>

                @endif
            </div>

        @else
            <!-- Formulario de Pre-registro Obligatorio -->
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6 border border-slate-200">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Inscripción de Socio (Ley 19.418)</h3>
                    <p class="text-sm text-slate-500">Ingresa tus datos personales, adjunta la documentación obligatoria y selecciona la fecha para acudir a firmar el libro físico.</p>
                </div>

                <form action="{{ route('portal.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- RUT -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">RUT *</label>
                            <input type="text" name="rut" value="{{ old('rut') }}" placeholder="12345678-9" required class="w-full rounded-lg border-slate-300 text-sm">
                            @error('rut') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fecha Nacimiento -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento *</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required class="w-full rounded-lg border-slate-300 text-sm">
                            @error('fecha_nacimiento') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nombres -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nombres *</label>
                            <input type="text" name="nombres" value="{{ old('nombres', Auth::user()->name) }}" required class="w-full rounded-lg border-slate-300 text-sm">
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Apellidos *</label>
                            <input type="text" name="apellidos" value="{{ old('apellidos') }}" required class="w-full rounded-lg border-slate-300 text-sm">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Dirección de Residencia *</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}" required class="w-full rounded-lg border-slate-300 text-sm">
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono *</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}" placeholder="+56912345678" required class="w-full rounded-lg border-slate-300 text-sm">
                        </div>

                        <!-- Meses Residencia -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Meses de Residencia en el sector *</label>
                            <input type="number" name="meses_residencia" value="{{ old('meses_residencia', 6) }}" min="1" required class="w-full rounded-lg border-slate-300 text-sm">
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    <!-- Documentos Adjuntos OBLIGATORIOS -->
                    <h4 class="font-bold text-slate-800 text-base">Archivos Requeridos (Obligatorios)</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">1. Cédula de Identidad (PDF/Imagen) *</label>
                            <input type="file" name="cedula" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 font-medium">
                            @error('cedula') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">2. Comprobante de Domicilio *</label>
                            <input type="file" name="comprobante_domicilio" accept=".pdf,.jpg,.jpeg,.png" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 font-medium">
                            @error('comprobante_domicilio') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    <!-- Agendamiento de Cita -->
                    <h4 class="font-bold text-slate-800 text-base">Agendamiento de Firma Presencial en Sede</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Selecciona un Horario Disponible *</label>
                            <select name="fecha_cita_firma" required class="w-full rounded-lg border-slate-300 text-sm">
                                <option value="">-- Seleccionar horario --</option>
                                @foreach($horariosDisponibles as $bloque)
                                    <option value="{{ $bloque->fecha_hora_inicio }}">
                                        {{ $bloque->fecha_hora_inicio->format('d/m/Y - H:i') }} a {{ $bloque->fecha_hora_fin->format('H:i') }} hrs (Cupos disponibles: {{ $bloque->cupos_disponibles }})
                                    </option>
                                @endforeach
                            </select>
                            @if($horariosDisponibles->isEmpty())
                                <p class="text-xs text-amber-600 mt-1">No hay horarios disponibles actualmente. Por favor intenta más tarde.</p>
                            @endif
                            @error('fecha_cita_firma') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Observaciones / Comentarios (Opcional)</label>
                            <textarea name="observaciones_cita" rows="2" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Ej: Acudiré acompañado de mi cónyuge.">{{ old('observaciones_cita') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-md">
                            Enviar Pre-registro y Confirmar Cita
                        </button>
                    </div>
                </form>
            </div>
        @endif

    </div>
</x-app-layout>