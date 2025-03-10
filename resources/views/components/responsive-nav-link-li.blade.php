@props(['active', 'href'])

@php
    $classes = $active ? 'active' : '';
    $attributes = $attributes->class(['w-full', 'h-full', 'inline-block', $classes]);

    if ($active) {
        $attributes = $attributes->merge(['aria-current' => 'page']);
    }
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes }}>{{ $slot }}</a>
</li>
