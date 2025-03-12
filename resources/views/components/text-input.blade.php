@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-transparent border-gray-500 focus:border-blue-300 focus:ring-indigo-500 rounded-md shadow-sm text-white']) !!}>
