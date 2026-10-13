<aside 
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="absolute left-0 top-0 z-9999 flex h-screen w-72 flex-col overflow-y-hidden bg-slate-900 duration-300 ease-linear lg:static lg:translate-x-0"
    @click.outside="sidebarOpen = false"
>
    <!-- Header del Sidebar / LOGO -->
    <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-600 text-white font-bold text-xl">
                JV
            </div>
            <div class="flex flex-col">
                <span class="text-lg font-bold text-white leading-tight">JJVV Digital</span>
                <span class="text-xs text-slate-400">Ley 19.418</span>
            </div>
        </a>

        <!-- Botón cerrar (Móvil) -->
        <button @click="sidebarOpen = false" class="block lg:hidden text-slate-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Menú de Navegación -->
    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
        <nav class="mt-5 px-4 py-4 lg:mt-9 lg:px-6">
            <div>
                <h3 class="mb-4 ml-4 text-xs font-semibold uppercase text-slate-400 tracking-wider">
                    Menú Principal
                </h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('dashboard') }}" 
                           class="group relative flex items-center gap-3.5 rounded-lg px-4 py-2.5 font-medium text-slate-300 duration-300 ease-in-out hover:bg-slate-800 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Panel General
                        </a>
                    </li>

                    <!-- Registro de Socios -->
                    <li>
                        <a href="#" 
                           class="group relative flex items-center gap-3.5 rounded-lg px-4 py-2.5 font-medium text-slate-300 duration-300 ease-in-out hover:bg-slate-800 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Socios & Libro RO-01
                        </a>
                    </li>

                    <!-- Emisión de Certificados -->
                    <li>
                        <a href="#" 
                           class="group relative flex items-center gap-3.5 rounded-lg px-4 py-2.5 font-medium text-slate-300 duration-300 ease-in-out hover:bg-slate-800 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Certificados Emitidos
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Módulos Futuros (Sprint 2 & 3) -->
            <div>
                <h3 class="mb-4 ml-4 text-xs font-semibold uppercase text-slate-400 tracking-wider">
                    Gestión & Finanzas
                </h3>

                <ul class="mb-6 flex flex-col gap-1.5">
                    <!-- Tesorería / Finanzas -->
                    <li>
                        <a href="#" class="group relative flex items-center gap-3.5 rounded-lg px-4 py-2.5 font-medium text-slate-400 duration-300 ease-in-out hover:bg-slate-800 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Ingresos & Egresos
                        </a>
                    </li>

                    <!-- Reservas Sede -->
                    <li>
                        <a href="#" class="group relative flex items-center gap-3.5 rounded-lg px-4 py-2.5 font-medium text-slate-400 duration-300 ease-in-out hover:bg-slate-800 hover:text-white">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Reservas de Sede
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</aside>