@props([
    'title' => 'Provinsi',
    'value' => 34,
])

<div class="bg-white rounded-xl p-6 shadow-md">
    <div class="text-3xl font-bold text-indigo-600 mb-2">{{ $value }}</div>
    <div class="text-sm text-gray-600">{{ $title }}</div>
</div>
