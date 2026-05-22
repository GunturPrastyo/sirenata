@props([
    'align' => 'left'
])

@php
    $alignClass = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right'
    ][$align] ?? 'text-left';
@endphp

<th {{ $attributes->merge(['class' => "px-4 py-3 bg-slate-100/80 text-slate-500 font-bold uppercase text-xs border-b border-slate-200 {$alignClass}"]) }}>
    {{ $slot }}
</th>
