@props(['disabled' => false, 'value' => null, 'max' => null, 'rows' => null])

<textarea
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'bg-transparent border-gray-700 focus:border-blue-300 focus:ring-indigo-500 rounded-md shadow-sm text-white']) !!}
    {{ $max ? 'maxlength='.(int)$max : '' }}
    {{ $rows ? 'rows='.(int)$rows : '' }}
>
@if($value){{ $value }}@endif
</textarea>
