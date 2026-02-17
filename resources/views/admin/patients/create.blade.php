<x-admin-layout title="Registrar Paciente | Simify" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => 'Registrar Nuevo Paciente'],
]">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Registrar Nuevo Paciente</h1>
            <p class="text-gray-600 mb-8">Complete los datos del paciente para registrarlo en el sistema médico.</p>

            <form action="{{ route('admin.patients.store') }}" method="POST">
                @csrf

                <!-- Datos del Usuario -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h2 class="text-lg font-semibold text-blue-900 mb-4">
                        <i class="fas fa-user me-2"></i>Datos de Cuenta de Usuario
                    </h2>
                    <p class="text-sm text-blue-800 mb-4">Información necesaria para la sesión en la aplicación</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                            <input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Juan Pérez García"
                                value="{{ old('name') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
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
                                placeholder="paciente@ejemplo.com"
                                value="{{ old('email') }}"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                            />
                            @error('email')
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
                            <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">Cédula/ID *</label>
                            <input
                                type="text"
                                name="id_number"
                                id="id_number"
                                placeholder="1234567890"
                                value="{{ old('id_number') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('id_number') border-red-500 @enderror"
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
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('phone') border-red-500 @enderror"
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
                                placeholder="Calle Principal 123, Apto 4B"
                                value="{{ old('address') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('address') border-red-500 @enderror"
                            />
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Datos Médicos -->
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h2 class="text-lg font-semibold text-red-900 mb-4">
                        <i class="fas fa-heartbeat me-2"></i>Información Médica
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Sangre</label>
                            <select
                                name="blood_type_id"
                                id="blood_type_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('blood_type_id') border-red-500 @enderror"
                            >
                                <option value="">-- Seleccionar tipo de sangre --</option>
                                @forelse($bloodTypes ?? [] as $bloodType)
                                    <option value="{{ $bloodType->id }}" {{ old('blood_type_id') == $bloodType->id ? 'selected' : '' }}>
                                        {{ $bloodType->name }}
                                    </option>
                                @empty
                                    <option disabled>No hay tipos de sangre disponibles</option>
                                @endforelse
                            </select>
                            @error('blood_type_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">Alergias Conocidas</label>
                        <textarea
                            name="allergies"
                            id="allergies"
                            placeholder="Ej: Penicilina, mariscos, lácteos... (escribir 'Ninguna' si no tiene alergias)"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('allergies') border-red-500 @enderror"
                        >{{ old('allergies') }}</textarea>
                        @error('allergies')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">Historial Médico</label>
                        <textarea
                            name="medical_history"
                            id="medical_history"
                            placeholder="Enfermedades previas, cirugías, medicamentos actuales, condiciones crónicas, etc."
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('medical_history') border-red-500 @enderror"
                        >{{ old('medical_history') }}</textarea>
                        @error('medical_history')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Resumen -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle me-2 text-blue-600"></i>
                        <strong>Nota:</strong> Se creará una cuenta de usuario con los datos proporcionados. El paciente podrá acceder a la aplicación con su email y una contraseña que será generada automáticamente.
                    </p>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium transition">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium transition">
                        <i class="fas fa-plus me-2"></i>Registrar Paciente
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
