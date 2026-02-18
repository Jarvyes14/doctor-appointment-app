<x-admin-layout title="Crear Usuario | Simify" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ['name' => 'Nuevo Usuario'],
]">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Crear Nuevo Usuario</h1>
            <p class="text-gray-600 mb-6">Complete los datos de la cuenta para crear un nuevo usuario en el sistema.</p>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <!-- Datos de Sesión -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h2 class="text-lg font-semibold text-blue-900 mb-4">
                        <i class="fas fa-lock me-2"></i>Datos de Sesión
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Juan Pérez"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="usuario@ejemplo.com"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Contraseña *</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="••••••••"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña *</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                placeholder="••••••••"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            />
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Datos Personales -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <h2 class="text-lg font-semibold text-green-900 mb-4">
                        <i class="fas fa-id-card me-2"></i>Datos Personales
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">Número de Identificación</label>
                            <input
                                type="text"
                                name="id_number"
                                id="id_number"
                                placeholder="12345678"
                                value="{{ old('id_number') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            />
                            @error('id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                            <input
                                type="tel"
                                name="phone"
                                id="phone"
                                placeholder="+1 (555) 123-4567"
                                value="{{ old('phone') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            />
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                            <input
                                type="text"
                                name="address"
                                id="address"
                                placeholder="Calle Principal 123"
                                value="{{ old('address') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                            />
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Rol -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4 mb-6">
                    <h2 class="text-lg font-semibold text-purple-900 mb-4">
                        <i class="fas fa-user-tag me-2"></i>Rol de Usuario
                    </h2>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Seleccionar Rol *</label>
                        <select
                            name="role"
                            id="role"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        >
                            <option value="">-- Seleccionar Rol --</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-sm text-gray-500">
                            <i class="fas fa-info-circle me-1"></i>
                            Selecciona el rol que tendrá este usuario en el sistema.
                        </p>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium transition">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium transition">
                        <i class="fas fa-save me-2"></i>Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
