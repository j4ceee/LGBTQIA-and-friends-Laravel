@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-transparent border-gray-700 focus:border-blue-300 focus:ring-indigo-500 rounded-md shadow-sm text-white disabled:text-gray-500 disabled:border-gray-800']) !!}>{{ $slot }}</textarea>
