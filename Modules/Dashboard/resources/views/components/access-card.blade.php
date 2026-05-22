@props(['title', 'description', 'url', 'variant' => 'indigo'])

@php
    $variants = [
        'red' => [
            'border' => 'hover:border-red-500',
            'text' => 'group-hover:text-red-600 text-red-600',
        ],
        'purple' => [
            'border' => 'hover:border-purple-500',
            'text' => 'group-hover:text-purple-600 text-gray-900',
        ],
        'emerald' => [
            'border' => 'hover:border-emerald-500',
            'text' => 'text-gray-900 group-hover:text-emerald-600',
        ],
        'orange' => [
            'border' => 'hover:border-orange-500',
            'text' => 'group-hover:text-orange-600 text-gray-900',
        ],
        'indigo' => [
            'border' => 'hover:border-indigo-500',
            'text' => 'group-hover:text-indigo-600 text-gray-900',
        ],
    ];

    $variantClass = $variants[$variant] ?? $variants['indigo'];
@endphp

<a href="{{ $url }}"
    class="card-hover bg-white rounded-2xl p-8 border-2 border-gray-200
          group transition-all {{ $variantClass['border'] }}">

    <div class="flex flex-col items-center text-center">
        {{ $slot }}

        <h3 class="text-2xl font-bold text-gray-900 mb-3 transition-colors {{ $variantClass['text'] }}">
            {{ $title }}
        </h3>

        <p class="text-gray-600 mb-4">
            {{ $description }}
        </p>

        <span
            class="inline-flex items-center font-semibold transition-transform
                   group-hover:translate-x-2 {{ $variantClass['text'] }}">
            Akses Dashboard
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </span>

    </div>
</a>
