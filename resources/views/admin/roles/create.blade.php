<x-admin-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Crear Nuevo Rol</h1>

            <form action="{{ route('admin.roles.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-bold mb-2">
                        Nombre del Rol
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           class="border rounded w-full py-2 px-3 text-gray-700 @error('name') border-red-500 @enderror"
                           required>
                    @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-gray-700 font-bold mb-2">
                        Descripción (opcional)
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="border rounded w-full py-2 px-3 text-gray-700">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Crear Rol
                    </button>
                    <a href="{{ route('admin.roles.index') }}"
                       class="text-gray-600 hover:text-gray-800">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
