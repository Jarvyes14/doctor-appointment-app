<x-admin-layout title="Nuevo Doctor | Simify" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
    ['name' => 'Nuevo Doctor'],
]">
    <div class="max-w-4xl mx-auto">
        <x-wire-card title="Registrar Nuevo Doctor">
            <form action="{{ route('admin.doctors.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Información de Usuario</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-wire-input label="Nombre Completo" name="name" placeholder="Ej: Dr. Juan Pérez" required />
                        <x-wire-input label="Correo Electrónico" name="email" type="email" placeholder="juan@ejemplo.com" required />
                        <x-wire-input label="Teléfono" name="phone" placeholder="+52 123 456 7890" />

                        <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-wire-input label="Contraseña" name="password" type="password" required />
                            <x-wire-input label="Confirmar Contraseña" name="password_confirmation" type="password" required />
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Información Médica</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Especialidad --}}
                        <div>
                            <x-wire-native-select
                                label="Especialidad"
                                name="speciality_id"
                                placeholder="Seleccione una especialidad"
                                :options="$specialities"
                                option-label="name"
                                option-value="id"
                            />
                        </div>

                        {{-- Cédula --}}
                        <div>
                            <x-wire-input
                                label="Número de Licencia Médica"
                                name="medical_license_number"
                                placeholder="N/A"
                            />
                        </div>
                    </div>

                    {{-- Biografía --}}
                    <div class="mt-4">
                        <x-wire-textarea
                            label="Biografía / Perfil Profesional"
                            name="biography"
                            rows="4"
                            placeholder="Descripción breve del doctor..."
                        />
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <x-wire-button secondary href="{{ route('admin.doctors.index') }}">
                        Cancelar
                    </x-wire-button>
                    <x-wire-button primary type="submit">
                        Guardar Doctor
                    </x-wire-button>
                </div>
            </form>
        </x-wire-card>
    </div>
</x-admin-layout>

