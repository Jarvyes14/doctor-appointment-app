<div class="flex items-center space-x-2">
    <x-wire-button href="{{ route('admin.usuarios.edit', $user) }}" blue xs>
        <i class="fa-solid fa-pen-to-square"></i>
    </x-wire-button>

    @if($user->id !== 0)
        <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            {{-- Agregamos una clase 'btn-delete' para el script --}}
            <x-wire-button type="submit" class="btn-delete" red xs>
                <i class="fa-solid fa-trash"></i>
            </x-wire-button>
        </form>
    @endif
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Confirmación antes de borrar (Opcional pero recomendado)
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Esta acción no se puede deshacer",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            ejecutarEliminacion(this);
                        }
                    });
                });
            });
        });

        function ejecutarEliminacion(form) {
            const url = form.action;
            const formData = new FormData(form);

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Esto hace que request()->ajax() sea true
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
                .then(response => response.json().then(data => ({ status: response.status, body: data })))
                .then(res => {
                    if (res.status === 200) {
                        Swal.fire('¡Eliminado!', res.body.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.body.message || 'No se pudo eliminar', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Ocurrió un error inesperado en el servidor', 'error');
                });
        }
    </script>
@endpush
