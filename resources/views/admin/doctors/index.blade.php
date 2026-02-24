<x-admin-layout title="Doctores | Simify" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Doctores'
    ]
]">
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.doctors.create') }}" class="flex items-center gap-1">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo</span>
        </x-wire-button>
    </x-slot>

    @livewire('admin.doctor-table')

    @push('js')
        {{-- Scripts handled in actions.blade.php --}}
    @endpush
</x-admin-layout>

