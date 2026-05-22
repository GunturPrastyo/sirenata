@props([
    'name',
    'label' => null,
    'required' => false,
    'helper' => null,
])

@php
    $errorKey = str_replace(['[', ']'], ['.', ''], $name);
    $errorKey = rtrim($errorKey, '.');
@endphp

<div class="w-full">
    @if ($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2 {{ $required ? "after:ml-0.5 after:text-red-500 after:content-['*']" : '' }}">
            {{ $label }}
        </label>
    @endif

    <select 
        id="{{ $name }}" 
        name="{{ $name }}" 
        {{ $attributes->merge([
            'class' => 'w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 transition-all duration-200 bg-white ' . 
            ($errors->has($errorKey) 
                ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500 bg-red-50' 
                : 'border-slate-300 focus:ring-indigo-500 focus:border-indigo-500 text-slate-800')
        ]) }}
        {{ $required ? 'required' : '' }}
    >
        {{ $slot }}
    </select>

    @if ($helper)
        <p class="mt-1 text-xs text-slate-500">{{ $helper }}</p>
    @endif

    @error($errorKey)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
