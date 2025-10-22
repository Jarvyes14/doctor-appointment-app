<div>
    <div class="mb-4 py-2">
        <input type="text" wire:model.live="search" placeholder="Buscar roles..."
               class="border rounded px-4 py-2 w-full">
        <a href="{{ route('admin.roles.create') }}"
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Crear Rol
        </a>
    </div>

    <table class="min-w-full bg-white border">
        <thead>
        <tr class="bg-gray-100">
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">Nombre</th>
            <th class="px-4 py-2 border">Acciones</th>
            <th class="px-4 py-2 border">Creado en</th>
        </tr>
        </thead>
        <tbody>
        @forelse($roles as $role)
            <tr>
                <td class="px-4 py-2 border">{{ $role->id }}</td>
                <td class="px-4 py-2 border">{{ $role->name }}</td>
                <td class="px-4 py-2 border">
                    <button class="text-blue-500 px-2 bg-gray-200 rounded">Editar</button>
                    <button class="text-red-500 px-2 bg-gray-200 rounded">Eliminar</button>
                </td>
                <td class="px-4 py-2 border">{{ $role->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="px-4 py-2 border text-center">
                    No hay roles disponibles
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $roles->links() }}
    </div>
</div>
