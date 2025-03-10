@props(['active'])

@php
$classes = ($active ?? true)
            ? 'active'
            : '';
@endphp

<li>
    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</li>
