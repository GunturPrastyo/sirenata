@props(['title', 'description'])

<div class="card-hover bg-gray-50 rounded-2xl p-8 text-center border-2 border-gray-100">
    {{ $icon }}
    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $title }}</h3>
    <p class="text-gray-600">{{ $description }}</p>
</div>
