<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Junta de Vecinos Digital') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar de TailAdmin -->
        <x-admin.sidebar />

        <!-- Contenido principal -->
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <!-- Header de TailAdmin -->
            <x-admin.header />

            <!-- Área de trabajo -->
            <main class="p-4 md:p-6 2xl:p-10">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>