<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] text-[#1b1b18] min-h-screen">

<!-- Navbar -->
<nav class="bg-white border-b border-[#e3e3e0]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="/" class="text-xl font-semibold text-[#1b1b18]">Mi App</a>

            <!-- Botón móvil -->
            <button data-collapse-toggle="navbar" type="button" class="md:hidden text-gray-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <!-- Menú -->
            <div class="hidden md:flex space-x-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm rounded-md hover:bg-gray-100">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm rounded-md hover:bg-gray-100">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm rounded-md bg-[#1b1b18] text-white hover:bg-black">Register</a>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Menú móvil -->
        <div class="hidden md:hidden" id="navbar">
            <div class="space-y-2 pb-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="block px-4 py-2 text-sm rounded-md bg-[#1b1b18] text-white hover:bg-black">Register</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Contenido principal -->
<main class="max-w-4xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold mb-4">Bienvenido a Laravel + Flowbite</h1>
    <p class="text-gray-600 mb-6">Esta página ya tiene Flowbite activado. Puedes usar cualquier componente.</p>

    <!-- Botón para abrir modal -->
    <button data-modal-target="example-modal" data-modal-toggle="example-modal" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
        Abrir modal de ejemplo
    </button>

    <!-- Modal -->
    <div id="example-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 w-full md:inset-0 h-[calc(100%-1rem)] md:h-full">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <div class="relative bg-white rounded-lg shadow">
                <button type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center" data-modal-hide="example-modal">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
                <div class="p-6 text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">¡Hola!</h3>
                    <p class="text-gray-600">Este es un modal de ejemplo con Flowbite.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Flowbite JS -->
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>
