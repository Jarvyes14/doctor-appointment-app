@props(['initialTab' => 'tab1'])

<div x-data="{ activeTab: '{{ $initialTab }}' }" {{ $attributes->merge(['class' => 'w-full']) }}>
    <!-- Navegación de pestañas -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex space-x-4" aria-label="Tabs">
            {{ $header }}
        </nav>
    </div>

    <!-- Contenido de las pestañas -->
    <div class="tab-content">
        {{ $slot }}
    </div>
</div>

