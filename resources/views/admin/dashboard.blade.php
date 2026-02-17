<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard Admin',
        'href' => route('admin.dashboard'),
        'active' => request()->routeIs('admin.dashboard'),
    ],
]">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-6">Panel de Administración</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Tarjeta de Usuarios -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-2xl text-blue-500"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Usuarios</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ \App\Models\User::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Pacientes -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-user-injured text-2xl text-green-500"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Pacientes</h3>
                        <p class="text-2xl font-bold text-green-600">{{ \App\Models\Patient::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Doctores -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-stethoscope text-2xl text-purple-500"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Doctores</h3>
                        <p class="text-2xl font-bold text-purple-600">{{ \App\Models\Doctor::count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Citas -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-alt text-2xl text-red-500"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Citas</h3>
                        <p class="text-2xl font-bold text-red-600">{{ \App\Models\Appointment::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Opciones de Administración -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Gestión de Usuarios -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Gestión de Usuarios</h2>
                <p class="text-gray-600 mb-4">Administra los usuarios del sistema</p>
                <a href="{{ route('admin.usuarios.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Ver todos los usuarios
                </a>
                <a href="{{ route('admin.usuarios.create') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded ml-2">
                    Crear Usuario
                </a>
            </div>

            <!-- Gestión de Roles -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Gestión de Roles</h2>
                <p class="text-gray-600 mb-4">Administra los roles del sistema</p>
                <a href="{{ route('admin.roles.index') }}" class="inline-block bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded">
                    Ver Roles
                </a>
                <a href="{{ route('admin.roles.create') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded ml-2">
                    Crear Rol
                </a>
            </div>

            <!-- Gestión de Pacientes -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Gestión de Pacientes</h2>
                <p class="text-gray-600 mb-4">Administra los pacientes del sistema</p>
                <a href="{{ route('admin.patients.index') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Ver Pacientes
                </a>
                <a href="{{ route('admin.patients.create') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded ml-2">
                    Crear Paciente
                </a>
            </div>

            <!-- Información del Sistema -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">Información del Sistema</h2>
                <p class="text-gray-600 mb-2"><strong>Versión:</strong> 1.0</p>
                <p class="text-gray-600 mb-2"><strong>Rol Actual:</strong> Administrador</p>
                <p class="text-gray-600"><strong>Última actualización:</strong> {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</x-admin-layout>
