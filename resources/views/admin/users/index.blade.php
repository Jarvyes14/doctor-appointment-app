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

    @stack('js')

</x-admin-layout>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const url = this.action;
                    const formData = new FormData(this);

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}" // Laravel requiere el token
                        }
                    })
                        .then(async response => {
                            const data = await response.json();

                            if (response.status === 403) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '¡Error!',
                                    text: data.message,
                                });
                            } else if (response.ok) {
                                Swal.fire('¡Eliminado!', data.message, 'success')
                                    .then(() => location.reload());
                            }
                        });
                });
            });
        });
    </script>
@endpush
