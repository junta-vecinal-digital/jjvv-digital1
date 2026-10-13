<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'JJVV Digital' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ sidebarOpen: false }" 
    class="font-sans antialiased bg-slate-100 text-slate-900"
>
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Component -->
        <x-admin.sidebar />

        <!-- Content Area -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            
            <!-- Header Component -->
            <x-admin.header />

            <!-- Main Content -->
            <main class="w-full grow p-4 md:p-6 2xl:p-10">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>