@props(['disabled' => false, 'isChecked' => false])

<input type="checkbox" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'bg-transparent border-gray-700 focus:border-blue-300 focus:ring-indigo-500 rounded-md shadow-sm text-white']) !!} {{ $isChecked ? 'checked' : '' }}>
