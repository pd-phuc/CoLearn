@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                {{ __('messages.shopping_cart') }}
            </h1>
            <p class="text-sm text-slate-500 font-medium mt-1">{{ __('messages.cart_is_empty') }}</p>
        </div>

        @if ($items->isEmpty())
            <!-- Empty Cart State -->
            <div
                class="bg-white border border-slate-200/80 rounded-3xl p-12 text-center max-w-lg mx-auto shadow-xs space-y-5"
            >
                <div
                    class="w-20 h-20 rounded-full bg-orange-50 text-orange-500 mx-auto flex items-center justify-center"
                >
                    <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"
                        />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">{{ __('messages.empty_cart_title') }}</h3>
                    <p class="text-sm text-slate-500 mt-1 font-medium leading-relaxed">
                        {{ __('messages.empty_cart_subtitle') }}
                    </p>
                </div>
                <a
                    href="{{ route('courses.index') }}"
                    class="btn-primary inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-sm shadow-md shadow-orange-500/20"
                >
                    <span>{{ __('messages.browse_courses') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                        />
                    </svg>
                </a>
            </div>
        @else
            <!-- Cart Items & Summary Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Items Table / List -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach ($items as $course)
                        <div
                            class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs hover:border-slate-300 transition-colors"
                        >
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-20 h-14 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200 relative"
                                >
                                    @if ($course->thumbnail)
                                        <img
                                            src="{{ asset($course->thumbnail) }}"
                                            alt="{{ $course->title }}"
                                            class="w-full h-full object-cover"
                                        />
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center text-white font-extrabold text-xs"
                                        >
                                            CoLearn
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-base font-extrabold text-slate-900 leading-snug">
                                        <a
                                            href="{{ route('courses.show', $course->slug) }}"
                                            class="hover:text-orange-600 transition-colors"
                                        >
                                            {{ $course->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-slate-500 font-medium mt-1">
                                        {{ $course->category->name }} &bull;
                                        {{ $course->teacher->name ?? __('messages.default_instructor_name') }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100"
                            >
                                <span class="text-base font-black text-slate-900">
                                    {{ number_format($course->price, 0, ',', '.') }} VNĐ
                                </span>
                                <form action="{{ route('cart.remove', $course->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer"
                                        title="{{ __('messages.action') }}"
                                    >
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                            />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary Card -->
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-6">
                    <h3 class="text-lg font-black text-slate-900 border-b border-slate-100 pb-4">
                        {{ __('messages.order_summary') }}
                    </h3>

                    <!-- Coupon Form -->
                    <div>
                        @if ($coupon)
                            <div
                                class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center justify-between text-xs font-bold text-emerald-800"
                            >
                                <div class="flex items-center gap-2">
                                    <svg
                                        class="w-4 h-4 text-emerald-600"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 7h10M7 12h10m-5 5h5"
                                        />
                                    </svg>
                                    <span>{{ $coupon->code }}</span>
                                </div>
                                <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="text-emerald-700 hover:text-rose-600 font-extrabold cursor-pointer"
                                    >
                                        &times;
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input
                                    type="text"
                                    name="code"
                                    placeholder="{{ __('messages.coupon_code') }}"
                                    class="input-field py-2 text-xs uppercase"
                                    required
                                />
                                <button type="submit" class="btn-secondary px-4 py-2 text-xs font-bold shrink-0">
                                    {{ __('messages.apply_coupon') }}
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="space-y-3 text-sm font-semibold text-slate-600 border-t border-slate-100 pt-4">
                        <div class="flex justify-between">
                            <span>{{ __('messages.subtotal') }}</span>
                            <span class="text-slate-900">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span>
                        </div>
                        @if ($discount > 0)
                            <div class="flex justify-between text-emerald-600 font-bold">
                                <span>{{ __('messages.discount') }}</span>
                                <span>- {{ number_format($discount, 0, ',', '.') }} VNĐ</span>
                            </div>
                        @endif

                        <div
                            class="flex justify-between text-lg font-black text-slate-900 border-t border-slate-100 pt-3"
                        >
                            <span>{{ __('messages.total') }}</span>
                            <span class="text-orange-600">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <a
                        href="{{ route('checkout.index') }}"
                        class="btn-primary w-full py-3.5 rounded-xl text-center font-extrabold text-sm flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20"
                    >
                        <span>{{ __('messages.proceed_to_checkout') }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
