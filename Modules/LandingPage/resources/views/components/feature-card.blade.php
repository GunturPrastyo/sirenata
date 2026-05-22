@props([
    'title',
    'description',
])

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 rounded-2xl p-7 hover:shadow-lg transition-shadow']) }}>
    @if(isset($icon))
        <div class="mb-5">
            {{ $icon }}
        </div>
    @endif
    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-sm text-gray-500 leading-relaxed">{{ $description }}</p>
</div>

