@extends('admin.layouts.admin')

@section('admin-content')

{{-- KPI Cards Row --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    {{-- Revenue --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('admin.revenue') }}</p>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900">{{ number_format($stats['revenue']['value'], 0, ',', '.') }} <span class="text-sm font-bold text-slate-400">đ</span></p>
        @if($stats['revenue']['change'] !== null)
            <p class="mt-1 text-xs font-bold {{ $stats['revenue']['change'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $stats['revenue']['change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['revenue']['change']) }}%
                <span class="text-slate-400 font-medium">{{ __('admin.vs_last_month') }}</span>
            </p>
        @endif
    </div>

    {{-- Orders --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('admin.orders') }}</p>
            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900">{{ $stats['orders']['value'] }}</p>
        @if($stats['orders']['change'] !== null)
            <p class="mt-1 text-xs font-bold {{ $stats['orders']['change'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $stats['orders']['change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['orders']['change']) }}%
                <span class="text-slate-400 font-medium">{{ __('admin.vs_last_month') }}</span>
            </p>
        @endif
    </div>

    {{-- New Students --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('admin.new_students') }}</p>
            <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900">{{ $stats['new_users']['value'] }}</p>
        @if($stats['new_users']['change'] !== null)
            <p class="mt-1 text-xs font-bold {{ $stats['new_users']['change'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $stats['new_users']['change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['new_users']['change']) }}%
                <span class="text-slate-400 font-medium">{{ __('admin.vs_last_month') }}</span>
            </p>
        @endif
    </div>

    {{-- Enrollments --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('admin.enrollments') }}</p>
            <div class="w-8 h-8 rounded-lg bg-violet-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
            </div>
        </div>
        <p class="text-2xl font-black text-slate-900">{{ $stats['enrollments']['value'] }}</p>
        @if($stats['enrollments']['change'] !== null)
            <p class="mt-1 text-xs font-bold {{ $stats['enrollments']['change'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                {{ $stats['enrollments']['change'] >= 0 ? '↑' : '↓' }} {{ abs($stats['enrollments']['change']) }}%
                <span class="text-slate-400 font-medium">{{ __('admin.vs_last_month') }}</span>
            </p>
        @endif
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    {{-- Revenue Chart --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <h3 class="text-sm font-extrabold text-slate-900 mb-4">{{ __('admin.revenue_12_months') }}</h3>
        <div class="h-64">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Enrollments Chart --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <h3 class="text-sm font-extrabold text-slate-900 mb-4">{{ __('admin.enrollments_30_days') }}</h3>
        <div class="h-64">
            <canvas id="enrollmentChart"></canvas>
        </div>
    </div>
</div>

{{-- Data Tables Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    {{-- Top Courses --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-extrabold text-slate-900">{{ __('admin.top_courses') }}</h3>
        </div>
        @forelse($topCourses as $course)
            <div class="mb-3 last:mb-0">
                <div class="flex items-center justify-between mb-1">
                    <p class="text-xs font-bold text-slate-700 truncate pr-2">{{ $course->title }}</p>
                    <span class="text-xs font-extrabold text-slate-500 shrink-0">{{ $course->enrollments_count }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-1.5">
                    <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-1.5 rounded-full" style="width: {{ ($course->enrollments_count / $maxEnrollments) * 100 }}%"></div>
                </div>
            </div>
        @empty
            <p class="text-xs text-slate-400 text-center py-4">{{ __('admin.no_enrollment_data') }}</p>
        @endforelse
    </div>

    {{-- Recent Orders --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-extrabold text-slate-900">{{ __('admin.recent_orders') }}</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700">{{ __('admin.view_all') }}</a>
        </div>
        <div class="space-y-3">
            @forelse($recentOrders as $order)
                <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-between hover:bg-slate-50 -mx-2 px-2 py-1.5 rounded-lg transition-colors">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $order->user?->name ?? 'N/A' }}</p>
                        <p class="text-[10px] text-slate-400 font-mono">{{ $order->order_number }}</p>
                    </div>
                    <div class="text-right shrink-0 ml-2">
                        <p class="text-xs font-extrabold text-slate-900">{{ number_format($order->total_amount, 0, ',', '.') }} đ</p>
                        <span class="inline-block px-1.5 py-0.5 text-[9px] font-bold uppercase rounded
                            {{ $order->status === 'paid' ? 'bg-emerald-100 text-emerald-700' : ($order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                            {{ $order->status }}
                        </span>
                    </div>
                </a>
            @empty
                <p class="text-xs text-slate-400 text-center py-4">{{ __('admin.no_orders_yet') }}</p>
            @endforelse
        </div>
    </div>

    {{-- Pending Review --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-extrabold text-slate-900">{{ __('admin.pending_review') }}</h3>
            @if($stats['pending_courses'] > 0)
                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full">{{ $stats['pending_courses'] }}</span>
            @endif
        </div>
        <div class="space-y-3">
            @forelse($pendingCourses as $course)
                <a href="{{ route('admin.courses.show', $course) }}" class="flex items-center justify-between hover:bg-slate-50 -mx-2 px-2 py-1.5 rounded-lg transition-colors">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-800 truncate">{{ $course->title }}</p>
                        <p class="text-[10px] text-slate-400">{{ $course->teacher?->name ?? 'Unknown' }}</p>
                    </div>
                    <span class="text-[10px] text-slate-400 shrink-0 ml-2">{{ $course->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-xs text-slate-400 text-center py-4">{{ __('admin.no_pending_courses') }}</p>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartDefaults = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 10, weight: 'bold', family: 'Plus Jakarta Sans' }, color: '#94a3b8' },
                border: { display: false },
            },
            y: {
                grid: { color: '#f1f5f9' },
                ticks: { font: { size: 10, weight: 'bold', family: 'Plus Jakarta Sans' }, color: '#94a3b8' },
                border: { display: false },
                beginAtZero: true,
            },
        },
    };

    // Revenue Line Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($revenueChart['labels']),
            datasets: [{
                data: @json($revenueChart['values']),
                borderColor: '#f97316',
                backgroundColor: 'rgba(249, 115, 22, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: '#f97316',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2,
            }],
        },
        options: {
            ...chartDefaults,
            scales: {
                ...chartDefaults.scales,
                y: {
                    ...chartDefaults.scales.y,
                    ticks: {
                        ...chartDefaults.scales.y.ticks,
                        callback: v => (v >= 1000000 ? (v/1000000).toFixed(1) + 'M' : v >= 1000 ? (v/1000).toFixed(0) + 'K' : v),
                    },
                },
            },
        },
    });

    // Enrollment Bar Chart
    new Chart(document.getElementById('enrollmentChart'), {
        type: 'bar',
        data: {
            labels: @json($enrollmentChart['labels']),
            datasets: [{
                data: @json($enrollmentChart['values']),
                backgroundColor: 'rgba(249, 115, 22, 0.7)',
                borderRadius: 4,
                borderSkipped: false,
                maxBarThickness: 12,
            }],
        },
        options: {
            ...chartDefaults,
            scales: {
                ...chartDefaults.scales,
                x: {
                    ...chartDefaults.scales.x,
                    ticks: {
                        ...chartDefaults.scales.x.ticks,
                        maxTicksLimit: 10,
                    },
                },
                y: {
                    ...chartDefaults.scales.y,
                    ticks: {
                        ...chartDefaults.scales.y.ticks,
                        stepSize: 1,
                    },
                },
            },
        },
    });
});
</script>
