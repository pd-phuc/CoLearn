@extends('admin.layouts.admin')

@section('admin-content')
<div class="space-y-6">
    {{-- Filter Header --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
        <form class="flex flex-wrap items-center justify-between gap-4" method="GET">
            <div class="flex flex-wrap items-center gap-3 flex-1 min-w-[280px]">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="{{ __('admin.search_orders_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all">
                    <option value="">{{ __('admin.all_statuses') }}</option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('messages.status_paid') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('messages.status_pending') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('messages.status_cancelled') }}</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>{{ __('messages.status_refunded') }}</option>
                </select>
                <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-xs font-extrabold uppercase shadow-sm">{{ __('admin.filter') }}</button>
            </div>
            <span class="text-xs font-bold text-slate-500">{{ __('messages.showing_courses_count', ['total' => $orders->count()]) }}</span>
        </form>
    </div>

    {{-- Orders Data Table --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 border-b border-slate-200/80">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('admin.order_code') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('admin.customer') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.filter_price') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('admin.gateway') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.order_status') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.total') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.order_date') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-right">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($orders as $order)
                        @php
                            $sc = ['paid' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60', 'pending' => 'bg-amber-50 text-amber-700 border-amber-200/60', 'cancelled' => 'bg-slate-100 text-slate-500 border-slate-200', 'refunded' => 'bg-blue-50 text-blue-700 border-blue-200/60'];
                            $ml = ['sepay' => 'SePay VietQR', 'vnpay' => 'SePay VietQR', 'wallet' => 'CoLearn Wallet', 'stripe' => 'Stripe Card'];
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-900 font-mono text-xs font-extrabold rounded-lg border border-slate-200/80">
                                    {{ $order->order_number }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-900">
                                {{ $order->user?->name ?? 'N/A' }}
                                <p class="text-[10px] text-slate-400 font-normal">{{ $order->user?->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-xs font-extrabold text-slate-500 uppercase">
                                {{ $order->order_type }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-700">
                                {{ $ml[$order->payment_method] ?? ucfirst($order->payment_method) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider border {{ $sc[$order->status] ?? 'bg-slate-100 text-slate-500' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-orange-600">
                                {{ number_format($order->total_amount, 0, ',', '.') }} đ
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-500">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-orange-50 hover:text-orange-600 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ __('messages.view_details') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-xs font-bold text-slate-400">
                                {{ __('admin.no_courses_matching') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
