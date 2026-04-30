@props([
    'status' => 'unknown',
    'progress' => 0,
])

@php
    $statusConfig = match (strtolower($status)) {
        'completed'   => [
            'color' => 'bg-emerald-100 text-emerald-700',
            'label' => 'Selesai',
        ],
        'in_progress' => [
            'color' => 'bg-indigo-100 text-indigo-700',
            'label' => 'Sedang Berjalan',
        ],
        'enrolled'    => [
            'color' => 'bg-amber-100 text-amber-700',
            'label' => 'Terdaftar',
        ],
        default       => [
            'color' => 'bg-gray-100 text-gray-500',
            'label' => 'Tidak Diketahui',
        ],
    };

    $progressColor = match (true) {
        $progress === 100 => 'bg-emerald-500',
        $progress >= 50   => 'bg-indigo-500',
        $progress > 0     => 'bg-amber-500',
        default           => 'bg-gray-300',
    };
@endphp

<div class="space-y-2">
    {{-- Status Badge --}}
    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusConfig['color'] }}">
        {{ $statusConfig['label'] }}
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