<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
         'active' => request()->routeIs('admin.dashboard'),
    ],
    [
        'name' => 'Sid',
        'href' => route('admin.roles.index'),
        'active' => request()->routeIs('profile.show'),
    ],
]">
    Hola desde Admin
</x-admin-layout>
