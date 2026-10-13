<header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1 border-b border-slate-200">
    <div class="flex flex-grow items-center justify-between px-4 py-4 shadow-2 md:px-6 2xl:px-11">
        
        <!-- Botón Toggle Sidebar (Móvil) -->
        <div class="flex items-center gap-2 sm:gap-4 lg:hidden">
            <button 
                @click.stop="sidebarOpen = !sidebarOpen"
                class="z-99999 block rounded-sm border border-slate-200 bg-white p-1.5 shadow-sm"
            >
                <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <!-- Título o Búsqueda -->
        <div class="hidden sm:block">
            <h2 class="text-sm font-semibold text-slate-600 uppercase tracking-wider">
                Panel de Administración Vecinal
            </h2>
        </div>

        <!-- Área de Usuario / Logout -->
        <div class="flex items-center gap-3">
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button 
                    @click="dropdownOpen = !dropdownOpen"
                    class="flex items-center gap-3 focus:outline-none"
                >
                    <span class="hidden text-right lg:block">
                        <span class="block text-sm font-semibold text-slate-800">
                            {{ Auth::user()->name ?? 'Usuario' }}
                        </span>
                        <span class="block text-xs text-slate-500 capitalize">
                            {{ Auth::user()->role->nombre ?? 'Sin Rol' }}
                        </span>
                    </span>

                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold border border-indigo-200">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}
                    </div>
                </button>

                <!-- Menú desplegable Perfil -->
                <div 
                    x-show="dropdownOpen"
                    @click.outside="dropdownOpen = false"
                    x-transition
                    class="absolute right-0 mt-3 w-48 rounded-md border border-slate-100 bg-white py-1 shadow-lg"
                    style="display: none;"
                >
                    <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                        Mi Perfil
                    </a>
                    
                    <!-- Cerrar Sesión -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-red-600 hover:bg-slate-50">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>