<x-admin-layout title="Usuarios | Simify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
    ],
    [
        'name' => 'Usuarios',
        'href' => route('admin.usuarios.index'),
    ],
    [
        'name' => 'Editar',
    ],
]">
    <x-wire-card>
        <form action="{{ route('admin.usuarios.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nombre --}}
                <x-wire-input
                    label="Nombre completo"
                    name="name"
                    placeholder="Nombre del usuario"
                    value="{{ old('name', $user->name) }}"
                    required
                />

                {{-- Email --}}
                <x-wire-input
                    label="Correo electrónico"
                    name="email"
                    type="email"
                    placeholder="correo@ejemplo.com"
                    value="{{ old('email', $user->email) }}"
                    required
                />

                {{-- ID Number --}}
                <x-wire-input
                    label="Número de identificación"
                    name="id_number"
                    placeholder="12345678"
                    value="{{ old('id_number', $user->id_number) }}"
                />

                {{-- Teléfono --}}
                <x-wire-input
                    label="Teléfono"
                    name="phone"
                    placeholder="555-0000"
                    value="{{ old('phone', $user->phone) }}"
                />

                {{-- Tipo de Sangre --}}
                <div>
                    <label for="blood_type_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Sangre
                    </label>
                    <select
                        name="blood_type_id"
                        id="blood_type_id"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    >
                        <option value="">Seleccionar tipo</option>
                        @foreach($bloodTypes as $type)
                            <option value="{{ $type->id }}" {{ old('blood_type_id', $user->blood_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('blood_type_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dirección --}}
                <div class="md:col-span-2">
                    <x-wire-input
                        label="Dirección"
                        name="address"
                        placeholder="Calle Principal 123"
                        value="{{ old('address', $user->address) }}"
                    />
                </div>

                {{-- Rol --}}
                <div class="md:col-span-2">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Rol
                    </label>
                    <select
                        name="role"
                        id="role"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        required
                    >
                        <option value="">Seleccionar rol</option>
                        <option value="Administrador" {{ old('role', $user->roles->first()?->name) == 'Administrador' ? 'selected' : '' }}>
                            Administrador
                        </option>
                        <option value="Doctor" {{ old('role', $user->roles->first()?->name) == 'Doctor' ? 'selected' : '' }}>
                            Doctor
                        </option>
                        <option value="Recepcionista" {{ old('role', $user->roles->first()?->name) == 'Recepcionista' ? 'selected' : '' }}>
                            Recepcionista
                        </option>
                        <option value="Paciente" {{ old('role', $user->roles->first()?->name) == 'Paciente' ? 'selected' : '' }}>
                            Paciente
                        </option>
                    </select>
                    @error('role')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fa-solid fa-info-circle"></i>
                        Deja los campos de contraseña vacíos si no deseas cambiarla
                    </p>
                </div>

                <x-wire-input
                    label="Nueva contraseña"
                    name="password"
                    type="password"
                    placeholder="••••••••"
                />

                <x-wire-input
                    label="Confirmar contraseña"
                    name="password_confirmation"
                    type="password"
                    placeholder="••••••••"
                />
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <x-wire-button
                    type="button"
                    href="{{ route('admin.users.index') }}"
                    gray
                >
                    Cancelar
                </x-wire-button>
                <x-wire-button type="submit" blue>
                    <i class="fa-solid fa-save mr-1"></i>
                    Actualizar Usuario
                </x-wire-button>
            </div>

        </form>
    </x-wire-card>

</x-admin-layout>
