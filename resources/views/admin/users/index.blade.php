<x-admin-layout title="Usuarios | Simify" :breadcrumbs="[
[
    'name' => 'Dashboard',
    'href' => route('admin.dashboard')
],
[
    'name' => 'Usuarios'
]
]"
>
    <x-slot name="action">
        <x-wire-button blue href="{{route('admin.users.create')}}" class="flex items-center gap-1">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo</span>
        </x-wire-button>
    </x-slot>

    @livewire('admin.data-tables.user-table')

</x-admin-layout>
