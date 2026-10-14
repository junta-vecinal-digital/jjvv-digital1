<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            Reserva de Hora - Firma de Libro
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

        @if(!$socio)
            <!-- Estado 1: Sin Solicitud -->
            <div class="bg-white p-6 rounded-lg border border-slate-200 text-center">
                <h3 class="text-lg font-bold text-slate-800 mb-2">No has iniciado tu solicitud de socio</h3>
                <p class="text-sm text-slate-500 mb-4">Primero debes enviar tus antecedentes en el portal antes de reservar una cita.</p>
                <a href="{{ route('portal.index') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-semibold text-sm">Ir al Formulario</a>
            </div>

        @elseif($socio->estado === 'pendiente')
            <!-- Estado 2: Pendiente de Revisión -->
            <div class="bg-amber-50 p-6 rounded-lg border border-amber-200">
                <h3 class="text-lg font-bold text-amber-800 mb-1">⏳ Documentos en Revisión</h3>
                <p class="text-sm text-amber-700">Tus antecedentes están siendo verificados por la directiva. Una vez aprobados pasará a estado <strong>Por Firmar</strong> y podrás agendar tu hora presencial.</p>
            </div>

        @elseif($socio->estado === 'activo')
            <!-- Estado 3: Socio Activo -->
            <div class="bg-emerald-50 p-6 rounded-lg border border-emerald-200">
                <h3 class="text-lg font-bold text-emerald-800 mb-1">✅ Socio Oficial Activo</h3>
                <p class="text-sm text-emerald-700">Tu firma en el libro físico ya fue verificada. No requieres agendar citas adicionales.</p>
            </div>

        @elseif($socio->estado === 'rechazado')
            <!-- Estado 4: Rechazado -->
            <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                <h3 class="text-lg font-bold text-red-800 mb-1">❌ Solicitud Rechazada</h3>
                <p class="text-sm text-red-700">Tu pre-registro no fue aprobado. Comunícate con la directiva para más información.</p>
            </div>

        @elseif($socio->estado === 'por_firmar')
            <!-- Estado 5: Por Firmar -->

            @if($citaReservada)
                <!-- Tiene una Cita Reservada -->
                <div class="bg-white p-6 rounded-lg border border-indigo-200 shadow-sm mb-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Tu Hora Reservada</h3>
                    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 text-indigo-900 mb-4">
                        <p class="text-xl font-bold">{{ $citaReservada->fecha_hora_inicio->format('d/m/Y') }} a las {{ $citaReservada->fecha_hora_inicio->format('H:i') }} hrs</p>
                        <p class="text-sm mt-1">Lugar: Sede Social de la Junta de Vecinos</p>
                    </div>

                    <form action="{{ route('citas.cancelar', $citaReservada) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Seguro que deseas cancelar esta hora?')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold">
                            Cancelar Reserva
                        </button>
                    </form>
                </div>
            @else
                <!-- No tiene cita y se muestran los horarios disponibles -->
                <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">Selecciona una Hora Disponible</h3>
                    <p class="text-sm text-slate-500 mb-6">Elige tu horario para acudir a firmar el Libro de Registro físico (RO-01).</p>

                    @forelse($citasDisponibles as $fecha => $bloques)
                        <div class="mb-6">
                            <h4 class="font-bold text-indigo-600 text-base mb-3 border-b border-indigo-100 pb-1">
                                {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                            </h4>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                @foreach($bloques as $bloque)
                                    <form action="{{ route('citas.reservar', $bloque) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full py-2 px-3 bg-slate-50 hover:bg-indigo-600 hover:text-white border border-slate-200 rounded-lg text-sm font-bold transition text-center">
                                            {{ $bloque->fecha_hora_inicio->format('H:i') }} hrs
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-slate-400 text-center py-6">No hay horarios disponibles en los próximos 30 días. Intenta más tarde.</p>
                    @endforelse
                </div>
            @endif

        @endif

    </div>
</x-app-layout>