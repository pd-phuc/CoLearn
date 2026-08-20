@props(['status'])

@php
    $config = match($status) {
        'published' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200/60', 'label' => __('teacher.status_published')],
        'pending_review' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200/60', 'label' => __('teacher.status_pending_review')],
        'draft' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-700', 'border' => 'border-amber-200/60', 'label' => __('teacher.status_draft')],
        'archived' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'border' => 'border-slate-200/60', 'label' => __('teacher.status_archived')],
        default => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'border' => 'border-slate-200/60', 'label' => $status],
    };
@endphp

<span {{ $attributes->merge(['class' => "px-2.5 py-1 {$config['bg']} {$config['text']} border {$config['border']} text-xs font-bold rounded-lg"]) }}>
    {{ $config['label'] }}
</span>
