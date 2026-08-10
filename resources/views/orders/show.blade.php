@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('messages.order_details') }}</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">{{ __('messages.order_number') }}{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('orders.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>{{ __('messages.back_to_orders') }}</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Receipt Card -->
    <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs space-y-8">
        
        <!-- Status & Meta Banner -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200/80">
            <div>
                <span class="text-xs text-slate-500 font-medium">{{ __('messages.order_status') }}:</span>
                <div class="mt-1">
                    @if($order->status === 'paid')
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-extrabold text-xs">
                            {{ __('messages.status_paid') }}
                        </span>
                    @elseif($order->status === 'pending')
                        <span class="px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-extrabold text-xs">
                            {{ __('messages.status_pending') }}
                        </span>
                    @else
                        <span class="px-3 py-1 rounded-full bg-slate-200 text-slate-700 font-extrabold text-xs">
                            {{ __('messages.status_cancelled') }}
                        </span>
                    @endif
                </div>
            </div>

            <div>
                <span class="text-xs text-slate-500 font-medium">{{ __('messages.order_date') }}:</span>
                <p class="text-xs font-extrabold text-slate-900 mt-1">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
            </div>

            <div>
                <span class="text-xs text-slate-500 font-medium">{{ __('messages.payment_method_label') }}:</span>
                <p class="text-xs font-extrabold text-slate-900 uppercase mt-1">{{ $order->payment_method }}</p>
            </div>

            @if($order->payment_id)
                <div>
                    <span class="text-xs text-slate-500 font-medium">{{ __('messages.transaction_id') }}:</span>
                    <p class="text-xs font-extrabold text-slate-900 mt-1">{{ $order->payment_id }}</p>
                </div>
            @endif
        </div>

        <!-- Enrolled Courses Item List -->
        <div class="space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                {{ __('messages.enrolled_courses') }}
            </h3>

            <div class="divide-y divide-slate-100 border border-slate-200/80 rounded-xl overflow-hidden">
                @foreach($order->items as $item)
                    <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-12 rounded-lg bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                @if($item->course->thumbnail)
                                    <img src="{{ asset($item->course->thumbnail) }}" alt="{{ $item->course->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center text-white font-extrabold text-[10px]">
                                        CoLearn
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900">
                                    <a href="{{ route('courses.show', $item->course->slug) }}" class="hover:text-orange-600 transition-colors">
                                        {{ $item->course->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $item->course->category->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between sm:justify-end gap-4">
                            <span class="text-sm font-black text-slate-900">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                            @if($order->status === 'paid')
                                <a href="{{ route('learn.show', $item->course->slug) }}" class="btn-primary py-2 px-4 rounded-xl text-xs font-bold shadow-xs">
                                    <span>Học ngay</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Totals Summary -->
        <div class="border-t border-slate-100 pt-4 space-y-2 text-xs font-semibold text-slate-600 max-w-xs ml-auto">
            <div class="flex justify-between">
                <span>{{ __('messages.subtotal') }}:</span>
                <span class="text-slate-900 font-bold">{{ number_format($order->subtotal, 0, ',', '.') }} VNĐ</span>
            </div>
            @if($order->discount_amount > 0)
                <div class="flex justify-between text-emerald-600 font-bold">
                    <span>{{ __('messages.discount') }} ({{ $order->coupon->code ?? '' }}):</span>
                    <span>- {{ number_format($order->discount_amount, 0, ',', '.') }} VNĐ</span>
                </div>
            @endif
            <div class="flex justify-between text-base font-black text-slate-900 border-t border-slate-100 pt-3">
                <span>{{ __('messages.total') }}:</span>
                <span class="text-orange-600">{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</span>
            </div>
        </div>

    </div>

</div>
@endsection
