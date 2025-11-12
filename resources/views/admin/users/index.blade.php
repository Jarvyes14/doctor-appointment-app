<x-admin-layout title="Usuarios | Simify" :breadcrumbs="[
[
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => 'Usuarios',
],
]"
>

    @livewire('admin.data-tables.user-table')
</x-admin-layout>
