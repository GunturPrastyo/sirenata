@props([
    'color' => 'slate',
    'text' => null
])

@php
    $colorClass = [
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
        'green' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
        'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200/60',
        'blue' => 'bg-blue-50 text-blue-700 border-blue-200/60',
        'primary' => 'bg-indigo-50 text-indigo-700 border-indigo-200/60',
        'amber' => 'bg-amber-50 text-amber-700 border-amber-200/60',
        'yellow' => 'bg-amber-50 text-amber-700 border-amber-200/60',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200/60',
        'red' => 'bg-red-50 text-red-700 border-red-200/60',
        'danger' => 'bg-red-50 text-red-700 border-red-200/60',
        'slate' => 'bg-slate-50 text-slate-700 border-slate-200/60',
        'gray' => 'bg-slate-50 text-slate-700 border-slate-200/60',
        'secondary' => 'bg-slate-50 text-slate-700 border-slate-200/60',
    ][$color] ?? 'bg-slate-50 text-slate-700 border-slate-200/60';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {$colorClass} transition-colors duration-150"]) }}>
    {{ $text ?? $slot }}
</span>
