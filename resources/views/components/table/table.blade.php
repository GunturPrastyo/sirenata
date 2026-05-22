@props([
    'plain' => false
])

<div {{ $attributes->only('class')->merge(['class' => 'overflow-x-auto ' . ($plain ? '' : 'border border-slate-200 rounded-xl bg-white shadow-sm')]) }}>
    <table {{ $attributes->except('class')->merge(['class' => 'min-w-full text-sm text-left text-slate-600']) }}>
        {{ $slot }}
    </table>
</div>
