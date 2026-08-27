@props([
    'user',
    'size' => 'md',
])

@php
    $sizeClasses = match ($size) {
        'xs' => 'w-7 h-7 text-xs',
        'sm' => 'w-9 h-9 text-sm',
        'md' => 'w-10 h-10 text-sm',
        'lg' => 'w-12 h-12 text-base',
        'xl' => 'w-16 h-16 text-xl',
        '2xl' => 'w-24 h-24 text-3xl',
        default => 'w-10 h-10 text-sm',
    };
    $initial = strtoupper(substr($user->name ?? '', 0, 1));
@endphp

@if ($user->avatar)
    <img
        src="{{ $user->avatar }}"
        alt="{{ $user->name }}"
        {{ $attributes->merge(['class' => "{$sizeClasses} rounded-full object-cover shrink-0"]) }}
    />
@else
    <div
        {{ $attributes->merge(['class' => "{$sizeClasses} rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-bold flex items-center justify-center shrink-0"]) }}
    >
        {{ $initial }}
    </div>
@endif
