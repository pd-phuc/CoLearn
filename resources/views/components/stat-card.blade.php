@props([
    'label',
    'value',
    'icon',
    'color' => 'blue',
    'suffix' => null,
    'change' => null,
    'changeLabel' => null,
    'subtitle' => null,
    'valueClass' => null,
])

@php
    $colorMap = [
        'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
        'blue' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
        'amber' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
        'violet' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600'],
        'indigo' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
        'rose' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600'],
    ];
    $c = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between">
    <div>
        <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ $label }}</p>
        <h3 class="text-2xl font-black mt-2 {{ $valueClass ?? 'text-slate-900' }}">
            {{ $value }}
            @if ($suffix)
                <span class="text-sm font-bold text-slate-400">{{ $suffix }}</span>
            @endif
        </h3>
        @if ($change !== null)
            <p class="mt-1 text-xs font-bold {{ $change >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $change >= 0 ? '↑' : '↓' }} {{ abs($change) }}%
                @if ($changeLabel)
                    <span class="text-slate-400 font-medium">{{ $changeLabel }}</span>
                @endif
            </p>
        @elseif ($subtitle)
            <p class="text-xs text-slate-400 font-semibold mt-1">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="w-12 h-12 rounded-2xl {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center shrink-0">
        {{ $icon }}
    </div>
</div>
