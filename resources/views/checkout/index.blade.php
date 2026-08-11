@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-8"
     x-data="{
         showQrModal: {{ isset($vietQrModal) && $vietQrModal ? 'true' : 'false' }},
         selectedMethod: '{{ $defaultMethod ?? 'wallet' }}',
         copiedMsg: '',
         copyToClipboard(text) {
             navigator.clipboard.writeText(text);
             this.copiedMsg = '{{ __('messages.copied_to_clipboard') }}';
             setTimeout(() => this.copiedMsg = '', 2000);
         }
     }">

    {{-- Page Header --}}
    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('messages.checkout_title') }}</h1>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        @csrf

        {{-- Payment Method Selection --}}
        <div class="md:col-span-2">
            <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
                <div class="flex items-center gap-3 p-6 border-b border-slate-100">
                    <div class="w-9 h-9 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ __('messages.select_payment_method') }}</h3>
                </div>

                <div class="p-6 space-y-3">
                    {{-- CoLearn Wallet --}}
                    @php
                        $userBalance = (float) auth()->user()->balance;
                        $hasEnough = $userBalance >= $total;
                    @endphp
                    <label @click="selectedMethod = 'wallet'"
                           :class="selectedMethod === 'wallet' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300 bg-white'"
                           class="group relative p-4 rounded-2xl border flex items-center justify-between cursor-pointer transition-all active:scale-[0.99]">
                        <div :class="selectedMethod === 'wallet' ? 'scale-100' : 'scale-0'"
                             class="absolute top-3 right-3 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-[9px] text-white transition-transform">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="wallet" x-model="selectedMethod" class="text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-black text-slate-900">{{ __('messages.wallet_payment') }}</p>
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[9px] font-black rounded-md uppercase tracking-wider">{{ __('messages.buy_one_click') }}</span>
                                </div>
                                <p class="text-xs text-slate-400 font-bold mt-0.5">
                                    {{ __('messages.current_balance') }}: <strong class="{{ $hasEnough ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($userBalance, 0, ',', '.') }} đ</strong>
                                    @if(! $hasEnough)
                                        <span class="text-rose-600 font-black ml-1">({{ __('messages.insufficient_balance') }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @if(! $hasEnough)
                            <a href="{{ route('wallet.index') }}" class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white font-black text-[10px] rounded-xl shadow-xs transition-colors shrink-0 uppercase tracking-wider">
                                {{ __('messages.topup_now') }}
                            </a>
                        @endif
                    </label>

                    {{-- SePay / VietQR --}}
                    @php $sepayConfigured = app(\App\Services\SePayService::class)->isConfigured(); @endphp
                    <label @click="{{ $sepayConfigured ? "selectedMethod = 'sepay'" : '' }}"
                           :class="selectedMethod === 'sepay' ? 'border-orange-500 bg-orange-50/50 ring-2 ring-orange-500/20' : 'border-slate-200 {{ $sepayConfigured ? 'hover:border-slate-300' : 'opacity-50 cursor-not-allowed' }} bg-white'"
                           class="group relative p-4 rounded-2xl border flex items-center justify-between {{ $sepayConfigured ? 'cursor-pointer' : 'cursor-not-allowed' }} transition-all active:scale-[0.99]">
                        <div :class="selectedMethod === 'sepay' ? 'scale-100' : 'scale-0'"
                             class="absolute top-3 right-3 flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 text-[9px] text-white transition-transform">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="sepay" x-model="selectedMethod" class="text-orange-600 focus:ring-orange-500" {{ $sepayConfigured ? '' : 'disabled' }}>
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ __('messages.vietqr_sepay_payment') }}</p>
                                @if(! $sepayConfigured)
                                    <p class="text-xs text-rose-500 font-bold">{{ __('messages.gateway_not_configured') }}</p>
                                @else
                                    <p class="text-xs text-slate-400 font-bold">{{ __('messages.vietqr_instruction_short') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="px-2.5 py-1 bg-blue-600 text-white font-black text-[9px] rounded-md uppercase tracking-wider">
                            VietQR
                        </div>
                    </label>

                    {{-- Stripe --}}
                    @php $stripeConfigured = app(\App\Services\StripeService::class)->isConfigured(); @endphp
                    <label @click="{{ $stripeConfigured ? "selectedMethod = 'stripe'" : '' }}"
                           :class="selectedMethod === 'stripe' ? 'border-indigo-500 bg-indigo-50/50 ring-2 ring-indigo-500/20' : 'border-slate-200 {{ $stripeConfigured ? 'hover:border-slate-300' : 'opacity-50 cursor-not-allowed' }} bg-white'"
                           class="group relative p-4 rounded-2xl border flex items-center justify-between {{ $stripeConfigured ? 'cursor-pointer' : 'cursor-not-allowed' }} transition-all active:scale-[0.99]">
                        <div :class="selectedMethod === 'stripe' ? 'scale-100' : 'scale-0'"
                             class="absolute top-3 right-3 flex h-5 w-5 items-center justify-center rounded-full bg-indigo-500 text-[9px] text-white transition-transform">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="stripe" x-model="selectedMethod" class="text-indigo-600 focus:ring-indigo-500" {{ $stripeConfigured ? '' : 'disabled' }}>
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ __('messages.stripe_payment') }}</p>
                                @if(! $stripeConfigured)
                                    <p class="text-xs text-rose-500 font-bold">{{ __('messages.gateway_not_configured') }}</p>
                                @else
                                    <p class="text-xs text-slate-400 font-bold">{{ __('messages.stripe_card_types') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="px-2.5 py-1 bg-indigo-600 text-white font-black text-[9px] rounded-md uppercase tracking-wider">
                            Stripe
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Order Summary Column --}}
        <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs sticky top-20">
            <div class="p-6 space-y-5">
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight border-b border-slate-100 pb-3">
                    {{ __('messages.order_summary') }}
                </h3>

                <div class="space-y-3">
                    @foreach($items as $item)
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-xs font-bold text-slate-700 line-clamp-2">{{ $item->title }}</span>
                            <span class="text-xs font-black text-slate-900 shrink-0">{{ number_format($item->price, 0, ',', '.') }} đ</span>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-dashed border-slate-200 pt-3 space-y-2 text-xs font-bold text-slate-500">
                    <div class="flex justify-between">
                        <span>{{ __('messages.subtotal') }}</span>
                        <span>{{ number_format($subtotal, 0, ',', '.') }} đ</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-emerald-600">
                            <span>{{ __('messages.discount') }}</span>
                            <span>- {{ number_format($discount, 0, ',', '.') }} đ</span>
                        </div>
                    @endif
                </div>

                <div class="flex justify-between text-base font-black text-slate-900 border-t border-slate-100 pt-3">
                    <span>{{ __('messages.total') }}</span>
                    <span class="text-orange-600">{{ number_format($total, 0, ',', '.') }} đ</span>
                </div>

                <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-black text-sm flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20 cursor-pointer uppercase tracking-wider active:scale-[0.98] transition-transform">
                    <span>{{ __('messages.pay_now') }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </button>
            </div>

            {{-- Secure Payment Badge --}}
            <div class="flex items-center justify-center gap-2 border-t border-slate-100 bg-slate-50/50 px-4 py-3">
                <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('messages.secure_payment_badge') }}</p>
            </div>
        </div>
    </form>

    {{-- VietQR Modal (shared component) --}}
    @if(isset($vietQrModal) && $vietQrModal && isset($vietQrData))
        <x-vietqr-modal :order="$order" :viet-qr-data="$vietQrData" />
    @endif

</div>
@endsection
