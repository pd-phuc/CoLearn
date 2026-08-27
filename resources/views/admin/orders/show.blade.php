@extends('admin.layouts.admin')
@section('page-title', 'Order Details: #' . $order->order_number)
@section('page-description', 'View transaction details, payment method, items, and issue refunds')

@section('admin-content')
    <div class="space-y-6">
        <a
            href="{{ route('admin.orders.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-orange-600 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Orders
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            {{-- Left Column: Order Summary & Purchased Items --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Summary Card --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block mb-1">
                                Order Number
                            </span>
                            <h2 class="text-lg font-black text-slate-900 font-mono">{{ $order->order_number }}</h2>
                        </div>
                        @php
                            $sc = [
                                'paid' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'cancelled' => 'bg-slate-100 text-slate-500 border-slate-200',
                                'refunded' => 'bg-blue-100 text-blue-700 border-blue-200',
                            ];
                        @endphp

                        <span
                            class="px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider border {{ $sc[$order->status] ?? 'bg-slate-100' }}"
                        >
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                        <div>
                            <span class="text-slate-400 font-extrabold uppercase tracking-wider block mb-1">
                                Customer
                            </span>
                            <p class="font-bold text-slate-900">{{ $order->user?->name ?? 'N/A' }}</p>
                            <p class="text-[10px] text-slate-400 font-medium">{{ $order->user?->email }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-extrabold uppercase tracking-wider block mb-1">
                                Order Type
                            </span>
                            <p class="font-bold text-slate-900 uppercase">{{ $order->order_type }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-extrabold uppercase tracking-wider block mb-1">
                                Payment Method
                            </span>
                            <p class="font-bold text-slate-900 uppercase">{{ $order->payment_method }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-extrabold uppercase tracking-wider block mb-1">
                                Total Amount
                            </span>
                            <p class="text-base font-black text-orange-600">
                                {{ number_format($order->total_amount, 0, ',', '.') }} đ
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Order Items --}}
                @if ($order->items->count())
                    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
                        <div
                            class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between"
                        >
                            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                                Purchased Items
                            </h3>
                            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                                {{ $order->items->count() }} Items
                            </span>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @foreach ($order->items as $item)
                                <div class="px-6 py-4 flex items-center justify-between">
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900">
                                            {{ $item->course?->title ?? 'Course Purchase' }}
                                        </h4>
                                        <p class="text-xs text-slate-400 font-medium mt-0.5">
                                            {{ $item->course?->category?->name ?? 'General' }}
                                        </p>
                                    </div>
                                    <span class="text-sm font-black text-slate-900">
                                        {{ number_format($item->price, 0, ',', '.') }} đ
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Right Column: Admin Actions --}}
            <div class="space-y-6">
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <h3
                        class="text-sm font-extrabold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3"
                    >
                        Administrative Controls
                    </h3>

                    <div>
                        <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block mb-1">
                            Created Timestamp
                        </span>
                        <p class="text-xs font-bold text-slate-700">{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                    </div>

                    @if ($order->paid_at)
                        <div>
                            <span class="text-xs font-extrabold text-slate-400 uppercase tracking-wider block mb-1">
                                Payment Timestamp
                            </span>
                            <p class="text-xs font-bold text-emerald-700">
                                {{ $order->paid_at->format('d/m/Y H:i:s') }}
                            </p>
                        </div>
                    @endif

                    @if ($order->status === 'paid')
                        <div class="pt-4 border-t border-slate-100">
                            <form
                                action="{{ route('admin.orders.refund', $order) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to refund this order?');"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 rounded-xl text-xs font-black uppercase cursor-pointer transition-colors"
                                >
                                    Refund Order
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
