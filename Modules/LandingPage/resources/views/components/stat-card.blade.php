@props([
    'title',
    'value',
])

<div {{ $attributes->merge(['class' => 'bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow']) }}>
    <div class="text-3xl font-extrabold mb-1" style="color: #13416B;">{{ $value }}</div>
    <div class="text-sm text-gray-500 font-medium">{{ $title }}</div>
</div>

