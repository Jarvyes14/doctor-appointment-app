<x-admin-layout title="Editar Paciente | Simify" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'href' => route('admin.patients.index')],
    ['name' => 'Editar Paciente'],
]">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Editar Paciente</h1>
            <p class="text-gray-600 mb-8">Actualice la información del paciente {{ $patient->user->name }}</p>

            <form action="{{ route('admin.patients.update', $patient->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-tabs :initialTab="$initialTab ?? 'usuario'">
                    <x-slot name="header">
                        <x-tab-link
                            tab="usuario"
                            icon="fas fa-user"
                            :hasErrors="$errors->hasAny(['name', 'email', 'id_number', 'phone', 'address'])"
                        >
                            Datos de Usuario
                        </x-tab-link>

                        <x-tab-link
                            tab="personales"
                            icon="fas fa-id-card"
                            :hasErrors="$errors->hasAny(['date_of_birth', 'gender', 'occupation', 'blood_type_id', 'allergies'])"
                        >
                            Datos Personales
                        </x-tab-link>

                        <x-tab-link
                            tab="medicos"
                            icon="fas fa-history"
                            :hasErrors="$errors->hasAny(['medical_history', 'family_medical_history', 'chronic_diseases', 'medications'])"
                        >
                            Antecedentes Médicos
                        </x-tab-link>

                        <x-tab-link
                            tab="salud"
                            icon="fas fa-heart"
                            :hasErrors="$errors->hasAny(['general_health_notes', 'smoker', 'drinker'])"
                        >
                            Información de Salud
                        </x-tab-link>

                        <x-tab-link
                            tab="emergencia"
                            icon="fas fa-phone-square"
                            :hasErrors="$errors->hasAny(['emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_phone'])"
                        >
                            Contacto de Emergencia
                        </x-tab-link>
                    </x-slot>

                    <!-- Tab 1: Datos de Usuario -->
                    <x-tab-content tab="usuario">
                        <x-wire-card class="mb-8">
                            <h2 class="text-lg font-semibold text-blue-900 mb-4">
                                <i class="fas fa-user me-2"></i>Datos de Cuenta de Usuario
                            </h2>
                            <p class="text-sm text-blue-800 mb-4">Información para la sesión en la aplicación</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old('name', $patient->user->name) }}"
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
                                        value="{{ old('email', $patient->user->email) }}"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                    />
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">Cédula/ID</label>
                                    <input
                                        type="text"
                                        name="id_number"
                                        id="id_number"
                                        value="{{ old('id_number', $patient->user->id_number) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('id_number') border-red-500 @enderror"
                                    />
                                    @error('id_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                    <x-wire-input name="phone" id="phone" type="tel" :value="old('phone', $patient->user->phone)" mask="(###) ###-####" class="w-full px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                    <input
                                        type="text"
                                        name="address"
                                        id="address"
                                        value="{{ old('address', $patient->user->address) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('address') border-red-500 @enderror"
                                    />
                                    @error('address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </x-wire-card>
                    </x-tab-content>

                    <!-- Tab 2: Datos Personales -->
                    <x-tab-content tab="personales">
                        <x-wire-card class="mb-8">
                            <h2 class="text-lg font-semibold text-green-900 mb-4">
                                <i class="fas fa-id-card me-2"></i>Datos Personales
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                                    <input
                                        type="date"
                                        name="date_of_birth"
                                        id="date_of_birth"
                                        value="{{ old('date_of_birth', $patient->date_of_birth) }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('date_of_birth') border-red-500 @enderror"
                                    />
                                    @error('date_of_birth')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Género</label>
                                    <select
                                        name="gender"
                                        id="gender"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('gender') border-red-500 @enderror"
                                    >
                                        <option value="">-- Seleccionar --</option>
                                        <option value="Masculino" {{ old('gender', $patient->gender) == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ old('gender', $patient->gender) == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                        <option value="Otro" {{ old('gender', $patient->gender) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('gender')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">Ocupación</label>
                                    <input
                                        type="text"
                                        name="occupation"
                                        id="occupation"
                                        value="{{ old('occupation', $patient->occupation) }}"
                                        placeholder="Profesión u ocupación"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('occupation') border-red-500 @enderror"
                                    />
                                    @error('occupation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Sangre</label>
                                    <select
                                        name="blood_type_id"
                                        id="blood_type_id"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('blood_type_id') border-red-500 @enderror"
                                    >
                                        <option value="">-- Seleccionar tipo de sangre --</option>
                                        @forelse($bloodTypes ?? [] as $bloodType)
                                            <option value="{{ $bloodType->id }}" {{ old('blood_type_id', $patient->blood_type_id) == $bloodType->id ? 'selected' : '' }}>
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

                                <div class="md:col-span-2">
                                    <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">Alergias Conocidas</label>
                                    <textarea
                                        name="allergies"
                                        id="allergies"
                                        placeholder="Ej: Penicilina, mariscos, lácteos..."
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 @error('allergies') border-red-500 @enderror"
                                    >{{ old('allergies', $patient->allergies) }}</textarea>
                                    @error('allergies')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </x-wire-card>
                    </x-tab-content>

                    <!-- Tab 3: Antecedentes Médicos -->
                    <x-tab-content tab="medicos">
                        <x-wire-card class="mb-8">
                            <h2 class="text-lg font-semibold text-purple-900 mb-4">
                                <i class="fas fa-history me-2"></i>Antecedentes Médicos
                            </h2>

                            <div class="mb-4">
                                <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">Historial Médico General</label>
                                <textarea
                                    name="medical_history"
                                    id="medical_history"
                                    placeholder="Enfermedades previas, cirugías realizadas, tratamientos anteriores..."
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('medical_history') border-red-500 @enderror"
                                >{{ old('medical_history', $patient->medical_history) }}</textarea>
                                @error('medical_history')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="family_medical_history" class="block text-sm font-medium text-gray-700 mb-2">Antecedentes Familiares</label>
                                <textarea
                                    name="family_medical_history"
                                    id="family_medical_history"
                                    placeholder="Enfermedades genéticas, condiciones hereditarias en la familia..."
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('family_medical_history') border-red-500 @enderror"
                                >{{ old('family_medical_history', $patient->family_medical_history) }}</textarea>
                                @error('family_medical_history')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="chronic_diseases" class="block text-sm font-medium text-gray-700 mb-2">Enfermedades Crónicas</label>
                                <textarea
                                    name="chronic_diseases"
                                    id="chronic_diseases"
                                    placeholder="Diabetes, hipertensión, asma, artritis, etc."
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('chronic_diseases') border-red-500 @enderror"
                                >{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                                @error('chronic_diseases')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="medications" class="block text-sm font-medium text-gray-700 mb-2">Medicamentos Actuales</label>
                                <textarea
                                    name="medications"
                                    id="medications"
                                    placeholder="Nombre del medicamento, dosis, frecuencia... (uno por línea)"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 @error('medications') border-red-500 @enderror"
                                >{{ old('medications', $patient->medications) }}</textarea>
                                @error('medications')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </x-wire-card>
                    </x-tab-content>

                    <!-- Tab 4: Información General de Salud -->
                    <x-tab-content tab="salud">
                        <x-wire-card class="mb-8">
                            <h2 class="text-lg font-semibold text-yellow-900 mb-4">
                                <i class="fas fa-heart me-2"></i>Información General de Salud
                            </h2>

                            <div class="mb-4">
                                <label for="general_health_notes" class="block text-sm font-medium text-gray-700 mb-2">Notas Generales de Salud</label>
                                <textarea
                                    name="general_health_notes"
                                    id="general_health_notes"
                                    placeholder="Observaciones sobre el estado general de salud, hábitos, estilo de vida..."
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-yellow-500 @error('general_health_notes') border-red-500 @enderror"
                                >{{ old('general_health_notes', $patient->general_health_notes) }}</textarea>
                                @error('general_health_notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="smoker"
                                        id="smoker"
                                        value="1"
                                        {{ old('smoker', $patient->smoker) ? 'checked' : '' }}
                                        class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded cursor-pointer"
                                    />
                                    <label for="smoker" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        <i class="fas fa-smoking me-1"></i>¿Fuma actualmente?
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        name="drinker"
                                        id="drinker"
                                        value="1"
                                        {{ old('drinker', $patient->drinker) ? 'checked' : '' }}
                                        class="h-4 w-4 text-yellow-600 focus:ring-yellow-500 border-gray-300 rounded cursor-pointer"
                                    />
                                    <label for="drinker" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                        <i class="fas fa-wine-glass me-1"></i>¿Consume alcohol?
                                    </label>
                                </div>
                            </div>
                        </x-wire-card>
                    </x-tab-content>

                    <!-- Tab 5: Contacto de Emergencia -->
                    <x-tab-content tab="emergencia">
                        <x-wire-card class="mb-8">
                            <h2 class="text-lg font-semibold text-red-900 mb-4">
                                <i class="fas fa-phone-square me-2"></i>Contacto de Emergencia
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                                    <input
                                        type="text"
                                        name="emergency_contact_name"
                                        id="emergency_contact_name"
                                        value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                                        placeholder="Nombre de contacto"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('emergency_contact_name') border-red-500 @enderror"
                                    />
                                    @error('emergency_contact_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 mb-2">Relación</label>
                                    <input
                                        type="text"
                                        name="emergency_contact_relationship"
                                        id="emergency_contact_relationship"
                                        value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}"
                                        placeholder="Ej: Padre, Esposo, Hermana..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('emergency_contact_relationship') border-red-500 @enderror"
                                    />
                                    @error('emergency_contact_relationship')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                    <x-wire-input name="emergency_contact_phone" id="emergency_contact_phone" type="tel" :value="old('emergency_contact_phone', $patient->emergency_contact_phone)" mask="(###) ###-####" placeholder="(555) 123-4567" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 @error('emergency_contact_phone') border-red-500 @enderror" />
                                    @error('emergency_contact_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </x-wire-card>
                    </x-tab-content>
                </x-tabs>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 mt-6">
                    <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 font-medium transition">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium transition">
                        <i class="fas fa-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
