<x-admin-layout title="Editar Doctor | Simify" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
    ['name' => 'Editar Doctor'],
]">
    <div class="max-w-4xl mx-auto">
        <x-wire-card title="Editar Perfil Médico: {{ $doctor->user->name }}">
            <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Usuario (Solo lectura) --}}
                    <div>
                        <x-wire-input
                            label="Usuario"
                            readonly
                            value="{{ $doctor->user->name }}"
                            disabled
                        />
                    </div>

                    {{-- Especialidad --}}
                    <div>
                        <x-wire-native-select
                            label="Especialidad"
                            name="speciality_id"
                            placeholder="{{ $doctor->speciality->name ?? 'Seleccione una especialidad' }}"
                            :options="$specialities->reject(fn($s) => $s->id == $doctor->speciality_id)"
                            option-label="name"
                            option-value="id"
                            :value="$doctor->speciality_id"
                        />
                    </div>

                    {{-- Cédula --}}
                    <div>
                        <x-wire-input
                            label="Número de Licencia Médica"
                            name="medical_license_number"
                            value="{{ $doctor->medical_license_number }}"
                        />
                    </div>
                </div>

                {{-- Biografía --}}
                <div>
                     <x-wire-textarea
                        label="Biografía / Perfil Profesional"
                        name="biography"
                        rows="4"
                    >{{ $doctor->biography }}</x-wire-textarea>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <x-wire-button secondary href="{{ route('admin.doctors.index') }}">
                        Cancelar
                    </x-wire-button>
                    <x-wire-button primary type="submit">
                        Actualizar Información
                    </x-wire-button>
                </div>
            </form>
        </x-wire-card>
    </div>
</x-admin-layout>

