<x-admin-layout title="Roles | Simify" :breadcrumbs="[
[
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => 'Roles',
],
]"
>
    <x-slot name="action">
        <x-wire-button blue href="{{route('admin.roles.create')}}" class="flex items-center gap-1">
            <i class="fa-solid fa-plus"></i>
            <span>Nuevo</span>
        </x-wire-button>
    </x-slot>

    @livewire('admin.role-table')
</x-admin-layout>
