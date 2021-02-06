@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900'
            : 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
