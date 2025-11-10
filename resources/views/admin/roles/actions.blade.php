<div class="flex items-center space-x-2">
    <x-wire-button href="{{ route('admin.roles.edit', $role) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" id="delete-role-{{ $role->id }}">
        @csrf
        @method('DELETE')

        <x-wire-button
            type='button'
            red
            xs
            onclick="confirmDeleteRole({{ $role->id }}, '{{ $role->name }}')"
        >
            <i class="fa-solid fa-trash"></i>
        </x-wire-button>
    </form>
</div>

<script>
    function confirmDeleteRole(roleId, roleName) {
        Swal.fire({
            title: "¿Estás seguro?",
            text: `¡El rol "${roleName}" se eliminará permanentemente!`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#EF4444",
            cancelButtonColor: "#6B7280",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            // Si el usuario presiona "Sí, eliminar", el JS envía el formulario al backend
            if (result.isConfirmed) {
                document.getElementById(`delete-role-${roleId}`).submit();
            }
        });
    }
</script>
