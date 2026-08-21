@props([
    'variant' => 'primary',
    'size' => 'md',
    'tag' => 'button',
    'href' => null,
    'type' => 'button',
])

@php
    $variants = [
        'primary'   => 'bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white shadow-sm hover:shadow-lg hover:shadow-orange-500/25',
        'secondary' => 'bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 border border-slate-200/80',
        'danger'    => 'bg-rose-500 hover:bg-rose-600 active:bg-rose-700 text-white shadow-sm hover:shadow-lg hover:shadow-rose-500/25',
        'ghost'     => 'bg-transparent hover:bg-slate-100 text-slate-600',
        'blue'      => 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white shadow-sm hover:shadow-lg hover:shadow-blue-500/25',
    ];

    $sizes = [
        'xs' => 'px-3 py-1.5 text-xs',
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $base = 'inline-flex items-center justify-center font-bold rounded-xl transition-all duration-200 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed active:scale-[0.98]';
    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
    $tag = $href ? 'a' : $tag;
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @endif
    @if($tag === 'button') type="{{ $type }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</{{ $tag }}>
