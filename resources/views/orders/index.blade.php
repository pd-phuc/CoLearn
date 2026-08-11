@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 space-y-6">

    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('messages.my_orders') }}</h1>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white border border-slate-200/80 rounded-2xl p-12 text-center max-w-md mx-auto space-y-4">
            <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900">{{ __('messages.empty_cart_title') }}</h3>
            <a href="{{ route('courses.index') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs">
                <span>{{ __('messages.browse_courses') }}</span>
            </a>
        </div>
    @else
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-semibold text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-6 py-4">{{ __('messages.order_number') }}</th>
                            <th class="px-6 py-4">{{ __('messages.order_date') }}</th>
                            <th class="px-6 py-4">{{ __('messages.payment_method_label') }}</th>
                            <th class="px-6 py-4">{{ __('messages.order_status') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('messages.order_total') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('messages.action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($orders as $order)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-900">{{ $order->order_number }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 uppercase font-bold text-slate-600">
                                    @php
                                        $methodLabels = ['sepay' => 'SePay', 'vnpay' => 'SePay', 'wallet' => 'Wallet', 'stripe' => 'Stripe'];
                                    @endphp
                                    {{ $methodLabels[$order->payment_method] ?? ucfirst($order->payment_method) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->status === 'paid')
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-extrabold">
                                            {{ __('messages.status_paid') }}
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold">
                                            {{ __('messages.status_pending') }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200 text-[10px] font-extrabold">
                                            {{ __('messages.status_cancelled') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-black text-slate-900">
                                    {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('orders.show', $order->id) }}" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-colors inline-flex items-center gap-1.5">
                                        <span>{{ __('messages.view_receipt') }}</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    @endif

</div>
@endsection
