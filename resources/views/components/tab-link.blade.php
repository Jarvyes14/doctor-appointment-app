@props(['tab', 'icon' => null, 'errors' => []])

@php
    // Verificar si alguno de los campos tiene error
    // $errors es la variable global de Blade (ViewErrorBag)
    $hasErrors = false;
    if (!empty($errors)) {
        foreach ($errors as $field) {
            // Usar la variable global $errors, no el array pasado como prop
            if (isset($errors) && $errors->has($field)) {
                $hasErrors = true;
                break;
            }
        }
    }
@endphp

<button
    type="button"
    @click="activeTab = '{{ $tab }}'"
    :class="{
        'border-blue-500 text-blue-600': activeTab === '{{ $tab }}',
        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== '{{ $tab }}',
        'border-red-500 text-red-600': {{ $hasErrors ? 'true' : 'false' }}
    }"
    class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200 flex items-center gap-2"
    {{ $attributes }}
>
    @if($hasErrors)
        <i class="fas fa-exclamation-triangle"></i>
    @elseif($icon)
        <i class="{{ $icon }}"></i>
    @endif
    {{ $slot }}
</button>
