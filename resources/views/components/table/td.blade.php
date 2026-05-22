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

<td {{ $attributes->merge(['class' => "px-4 py-3 text-slate-600 border-b border-slate-100 align-middle {$alignClass}"]) }}>
    {{ $slot }}
</td>
