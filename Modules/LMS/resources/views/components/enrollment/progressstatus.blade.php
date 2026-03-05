@props([
    'status' => 'unknown',
    'progress' => 0,
])

@php
    $statusColor = match ($status) {
        'completed' => 'bg-green-100 text-green-700',
        'ongoing' => 'bg-blue-100 text-blue-700',
        'failed' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-700',
    };

    $progressColor = $progress == 100 ? 'bg-green-500' : 'bg-blue-500';
@endphp

<div class="space-y-2">

    {{-- Status Badge --}}
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
        {{ ucfirst($status) }}
    </span>

    {{-- Progress Bar --}}
    <div class="w-full bg-slate-200 rounded-full h-2">
        <div class="h-2 rounded-full transition-all duration-500 {{ $progressColor }}"
            style="width: {{ $progress }}%">
        </div>
    </div>

    <p class="text-xs text-slate-500">
        {{ $progress }}% selesai
    </p>
</div>
