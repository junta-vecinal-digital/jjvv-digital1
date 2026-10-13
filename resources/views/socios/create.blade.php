<x-admin-layout>
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Registrar Nuevo Socio</h2>
            <p class="text-sm text-slate-500">Pre-registro de datos para ingreso al libro de socios (Ley 19.418)</p>
        </div>
        <a href="{{ route('socios.index') }}" class="px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
            Volver al listado
        </a>
    </div>

    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('socios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- RUT -->
                <div>
                    <label for="rut" class="block text-sm font-medium text-slate-700 mb-1">RUT *</label>
                    <input type="text" name="rut" id="rut" value="{{ old('rut') }}" placeholder="12345678-9" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('rut') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha de Nacimiento -->
                <div>
                    <label for="fecha_nacimiento" class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento *</label>
                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('fecha_nacimiento') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Nombres -->
                <div>
                    <label for="nombres" class="block text-sm font-medium text-slate-700 mb-1">Nombres *</label>
                    <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('nombres') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Apellidos -->
                <div>
                    <label for="apellidos" class="block text-sm font-medium text-slate-700 mb-1">Apellidos *</label>
                    <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('apellidos') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium text-slate-700 mb-1">Dirección (Pasaje / Calle #) *</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('direccion') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-slate-700 mb-1">Teléfono de Contacto *</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" placeholder="+56912345678" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('telefono') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Meses de Residencia -->
                <div>
                    <label for="meses_residencia" class="block text-sm font-medium text-slate-700 mb-1">Meses de Residencia en la Unidad Vecinal *</label>
                    <input type="number" name="meses_residencia" id="meses_residencia" value="{{ old('meses_residencia', 6) }}" min="1" required 
                           class="w-full rounded-lg border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <p class="text-xs text-slate-400 mt-1">Ley 19.418 requiere un mínimo de permanencia o residencia continua.</p>
                    @error('meses_residencia') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <hr class="border-slate-200 my-4">

            <h3 class="text-base font-semibold text-slate-800">Documentos Adjuntos (Opcional)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cédula de Identidad -->
                <div>
                    <label for="cedula" class="block text-sm font-medium text-slate-700 mb-1">Copia Cédula de Identidad (PDF/Imagen)</label>
                    <input type="file" name="cedula" id="cedula" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('cedula') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Comprobante Domicilio -->
                <div>
                    <label for="comprobante_domicilio" class="block text-sm font-medium text-slate-700 mb-1">Comprobante de Domicilio (Servicio/Boleta)</label>
                    <input type="file" name="comprobante_domicilio" id="comprobante_domicilio" accept=".pdf,.jpg,.jpeg,.png"
                           class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('comprobante_domicilio') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('socios.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-sm">
                    Guardar Pre-registro
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>