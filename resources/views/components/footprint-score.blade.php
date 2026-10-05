@props(['score'])

@php
    $level = match (true) {
        $score >= 75 => ['Excellent', 'bg-emerald-500'],
        $score >= 50 => ['Bon', 'bg-lime-500'],
        $score >= 25 => ['Moyen', 'bg-amber-500'],
        default => ['Faible', 'bg-orange-500'],
    };
@endphp

<span class="inline-flex items-center gap-2 rounded-md bg-white/95 px-2 py-1 text-xs font-semibold text-slate-800">
    <span class="h-2 w-2 rounded-full {{ $level[1] }}"></span>
    {{ $score }}/100 · {{ $level[0] }}
</span>
