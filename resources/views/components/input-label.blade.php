@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-gray-300']) }}>
    {{ $value ?? $slot }}
</label>
