@props(['title', 'description', 'url', 'color' => 'indigo'])

<a href="{{ $url }}"
    class="card-hover bg-white rounded-2xl p-8 border-2 border-gray-200
          hover:border-{{ $color }}-500 group transition-all">

    <div class="flex flex-col items-center text-center">

        <!-- Icon -->
        <div
            class="w-20 h-20 bg-{{ $color }}-100 rounded-2xl flex items-center justify-center mb-6
                   group-hover:bg-{{ $color }}-500 transition-all">
            {{ $slot }}
        </div>

        <!-- Title -->
        <h3
            class="text-2xl font-bold text-gray-900 mb-3
                   group-hover:text-{{ $color }}-600 transition-colors">
            {{ $title }}
        </h3>

        <!-- Description -->
        <p class="text-gray-600 mb-4">
            {{ $description }}
        </p>

        <!-- Action -->
        <span
            class="inline-flex items-center text-{{ $color }}-600 font-semibold
                   group-hover:translate-x-2 transition-transform">
            Akses Dashboard
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </span>

    </div>
</a>
