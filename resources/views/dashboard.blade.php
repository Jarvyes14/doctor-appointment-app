<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-bold mb-4">Bienvenido, {{ auth()->user()->name }}!</h3>

                <p class="text-gray-600 mb-6">
                    Parece que has llegado al dashboard general.
                    <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline font-bold">
                        Ir al Panel de Administración →
                    </a>
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Opciones Rápidas -->
                    <a href="{{ route('appointments.index') }}" class="block p-4 bg-blue-50 border border-blue-200 rounded hover:bg-blue-100">
                        <h4 class="font-bold text-blue-900">Mis Citas</h4>
                        <p class="text-sm text-blue-700">Ver y gestionar citas</p>
                    </a>

                    <a href="{{ route('doctors.index') }}" class="block p-4 bg-green-50 border border-green-200 rounded hover:bg-green-100">
                        <h4 class="font-bold text-green-900">Doctores</h4>
                        <p class="text-sm text-green-700">Directorio de doctores</p>
                    </a>

                    <a href="{{ route('admin.dashboard') }}" class="block p-4 bg-purple-50 border border-purple-200 rounded hover:bg-purple-100">
                        <h4 class="font-bold text-purple-900">Panel Admin</h4>
                        <p class="text-sm text-purple-700">Administrar sistema</p>
                    </a>
                </div>

                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
                    <p class="text-sm text-gray-600">
                        <strong>Tu rol:</strong> {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
