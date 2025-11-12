<x-admin-layout title="Roles | Simify" :breadcrumbs="[
[
    'name' => 'Dashboard',
    'href' => route('admin.dashboard'),
],
[
    'name' => 'Roles',
    'href' => route('admin.users.index'),
],
[
    'name' => 'Nuevo',
],
]"
>
    <x-wire-card>
        <form action="{{route('admin.usuarios.store')}}" method="POST">
            @csrf

            <x-wire-input label="Nombre" name="name" placeholder="Nombre del usuario" value="{{old('name')}}"></x-wire-input>
            <x-wire-input label="Email" name="email" placeholder="Correo electronico" value="{{old('email')}}"></x-wire-input>
            <x-wire-input label="Password" name="password" placeholder="Contraseña" value="{{old('password')}}"></x-wire-input>
            <div class="flex justify-end mt-4">
                <x-wire-button type="submit" blue>Guardar</x-wire-button>
            </div>

        </form>
    </x-wire-card>

</x-admin-layout>
