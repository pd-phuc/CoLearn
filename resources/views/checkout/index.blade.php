@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-8"
     x-data="{
         showQrModal: {{ isset($vietQrModal) && $vietQrModal ? 'true' : 'false' }},
         selectedMethod: 'vnpay',
         copiedMsg: '',
         copyToClipboard(text) {
             navigator.clipboard.writeText(text);
             this.copiedMsg = '{{ __('messages.copied_to_clipboard') }}';
             setTimeout(() => this.copiedMsg = '', 2000);
         }
     }">

    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('messages.checkout_title') }}</h1>
        <p class="text-sm text-slate-500 font-medium mt-1">{{ __('messages.select_payment_method') }}</p>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
        @csrf

        <!-- Payment Method Selection Cards -->
        <div class="md:col-span-2 space-y-6 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs">
            <h3 class="text-base font-extrabold text-slate-900 border-b border-slate-100 pb-3">
                {{ __('messages.select_payment_method') }}
            </h3>

            <div class="space-y-4">
                <!-- CoLearn Wallet Option (1-Click Instant) -->
                @php
                    $userBalance = (float) auth()->user()->balance;
                    $hasEnough = $userBalance >= $total;
                @endphp
                <label @click="selectedMethod = 'wallet'"
                       :class="selectedMethod === 'wallet' ? 'border-emerald-500 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-slate-200 hover:border-slate-300 bg-white'"
                       class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="payment_method" value="wallet" x-model="selectedMethod" class="text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-extrabold text-slate-900">{{ __('messages.wallet_payment') }}</p>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded uppercase">{{ __('messages.buy_one_click') }}</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium mt-0.5">
                                {{ __('messages.current_balance') }}: <strong class="{{ $hasEnough ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format($userBalance, 0, ',', '.') }} VNĐ</strong>
                                @if(! $hasEnough)
                                    <span class="text-rose-600 font-bold ml-1">({{ __('messages.insufficient_balance') }})</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @if(! $hasEnough)
                        <a href="{{ route('wallet.index') }}" class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-xs rounded-xl shadow-xs transition-colors shrink-0">
                            {{ __('messages.topup_now') }}
                        </a>
                    @else
                        <div class="px-2.5 py-1 bg-emerald-600 text-white font-black text-[10px] rounded uppercase tracking-wider">
                            {{ __('messages.colearn_wallet') }}
                        </div>
                    @endif
                </label>

                <!-- SePay / VietQR Option -->
                <label @click="selectedMethod = 'vnpay'"
                       :class="selectedMethod === 'vnpay' ? 'border-orange-500 bg-orange-50/50 ring-2 ring-orange-500/20' : 'border-slate-200 hover:border-slate-300 bg-white'"
                       class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="payment_method" value="vnpay" x-model="selectedMethod" class="text-orange-600 focus:ring-orange-500">
                        <div>
                            <p class="text-sm font-extrabold text-slate-900">{{ __('messages.vietqr_sepay_payment') }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ __('messages.vietqr_instruction_short') }}</p>
                        </div>
                    </div>
                    <div class="px-2.5 py-1 bg-blue-600 text-white font-black text-[10px] rounded uppercase tracking-wider">
                        VietQR
                    </div>
                </label>

                <!-- Stripe Option -->
                <label @click="selectedMethod = 'stripe'"
                       :class="selectedMethod === 'stripe' ? 'border-orange-500 bg-orange-50/50 ring-2 ring-orange-500/20' : 'border-slate-200 hover:border-slate-300 bg-white'"
                       class="p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all">
                    <div class="flex items-center gap-3">
                        <input type="radio" name="payment_method" value="stripe" x-model="selectedMethod" class="text-orange-600 focus:ring-orange-500">
                        <div>
                            <p class="text-sm font-extrabold text-slate-900">{{ __('messages.stripe_payment') }}</p>
                            <p class="text-xs text-slate-500 font-medium">{{ __('messages.stripe_card_types') }}</p>
                        </div>
                    </div>
                    <div class="px-2.5 py-1 bg-indigo-600 text-white font-black text-[10px] rounded uppercase tracking-wider">
                        Stripe
                    </div>
                </label>
            </div>
        </div>

        <!-- Checkout Summary Column -->
        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-6">
            <h3 class="text-base font-extrabold text-slate-900 border-b border-slate-100 pb-3">
                {{ __('messages.order_summary') }}
            </h3>

            <div class="space-y-3 text-xs font-semibold">
                @foreach($items as $item)
                    <div class="flex justify-between items-start gap-2">
                        <span class="text-slate-800 line-clamp-2">{{ $item->title }}</span>
                        <span class="text-slate-900 font-bold shrink-0">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                    </div>
                @endforeach
            </div>

            <div class="border-t border-slate-100 pt-3 space-y-2 text-xs font-bold text-slate-600">
                <div class="flex justify-between">
                    <span>{{ __('messages.subtotal') }}</span>
                    <span>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span>
                </div>
                @if($discount > 0)
                    <div class="flex justify-between text-emerald-600">
                        <span>{{ __('messages.discount') }}</span>
                        <span>- {{ number_format($discount, 0, ',', '.') }} VNĐ</span>
                    </div>
                @endif
                <div class="flex justify-between text-base font-black text-slate-900 border-t border-slate-100 pt-3">
                    <span>{{ __('messages.total') }}</span>
                    <span class="text-orange-600">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                </div>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-extrabold text-sm flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20 cursor-pointer">
                <span>{{ __('messages.pay_now') }}</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Embedded VietQR Interactive Modal -->
    @if(isset($vietQrModal) && $vietQrModal && isset($vietQrData))
        <div x-show="showQrModal"
             x-data="{
                 timeLeft: 900,
                 pollInterval: null,
                 timerInterval: null,
                 formatTime(seconds) {
                     const m = Math.floor(seconds / 60);
                     const s = seconds % 60;
                     return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                 },
                 init() {
                     this.timerInterval = setInterval(() => {
                         if (this.timeLeft > 0) this.timeLeft--;
                     }, 1000);

                     // Real-time polling check every 3 seconds
                     this.pollInterval = setInterval(async () => {
                         try {
                             const res = await fetch('/orders/{{ $order->id }}/status');
                             if (res.ok) {
                                 const data = await res.json();
                                 if (data.paid && data.redirect) {
                                     clearInterval(this.pollInterval);
                                     clearInterval(this.timerInterval);
                                     window.location.href = data.redirect;
                                 }
                             }
                         } catch (e) { console.error(e); }
                     }, 3000);
                 },
                 async simulatePayment() {
                     try {
                         const res = await fetch('/orders/{{ $order->id }}/simulated-pay', {
                             method: 'POST',
                             headers: {
                                 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                 'Accept': 'application/json'
                             }
                         });
                         if (res.ok) {
                             const data = await res.json();
                             if (data.redirect) {
                                 window.location.href = data.redirect;
                             }
                         }
                     } catch (e) { console.error(e); }
                 }
             }"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-6 relative overflow-hidden">
                
                <!-- Toast Notification for Copy -->
                <div x-show="copiedMsg" x-transition class="absolute top-4 right-4 bg-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md z-10">
                    <span x-text="copiedMsg"></span>
                </div>

                <!-- Modal Header -->
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-extrabold text-sm">
                            QR
                        </div>
                        <div>
                            <h3 class="text-base font-extrabold text-slate-900">{{ __('messages.scan_vietqr_to_pay') }}</h3>
                            <p class="text-xs text-slate-500 font-medium">{{ __('messages.order_number') }}{{ $vietQrData['order_number'] }}</p>
                        </div>
                    </div>
                    <button @click="showQrModal = false" class="text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Instruction Subtitle -->
                <p class="text-xs text-slate-600 text-center font-medium leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                    {{ __('messages.scan_qr_instruction') }}
                </p>

                <!-- VietQR Image Code Card -->
                <div class="flex flex-col items-center justify-center space-y-3">
                    <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm relative group">
                        <img src="{{ $vietQrData['qr_url'] }}" alt="VietQR Code" class="w-56 h-56 object-contain rounded-xl">
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                        <span>{{ __('messages.countdown_timer') }}:</span>
                        <span class="text-orange-600 font-mono text-sm font-extrabold" x-text="formatTime(timeLeft)"></span>
                    </div>
                </div>

                <!-- Bank Transfer Copyable Details -->
                <div class="space-y-2.5 text-xs font-semibold bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">{{ __('messages.bank_name_label') }}:</span>
                        <span class="text-slate-900 font-bold">{{ $vietQrData['bank_name'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">{{ __('messages.account_name_label') }}:</span>
                        <span class="text-slate-900 font-bold">{{ $vietQrData['account_name'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">{{ __('messages.bank_account_no') }}:</span>
                        <button @click="copyToClipboard('{{ $vietQrData['account_no'] }}')" class="flex items-center gap-1.5 text-orange-600 font-bold hover:underline cursor-pointer">
                            <span>{{ $vietQrData['account_no'] }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500">{{ __('messages.transfer_memo') }}:</span>
                        <button @click="copyToClipboard('{{ $vietQrData['order_number'] }}')" class="flex items-center gap-1.5 text-orange-600 font-bold hover:underline cursor-pointer">
                            <span class="font-mono">{{ $vietQrData['order_number'] }}</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-between items-center border-t border-slate-200 pt-2">
                        <span class="text-slate-500">{{ __('messages.total') }}:</span>
                        <span class="text-sm font-black text-orange-600">{{ $vietQrData['formatted_amount'] }}</span>
                    </div>
                </div>

                <!-- Real-time Status Indicator & Simulator Button -->
                <div class="space-y-3 text-center">
                    <div class="flex items-center justify-center gap-2 text-xs font-semibold text-slate-500 animate-pulse">
                        <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                        <span>{{ __('messages.waiting_payment_auto_detect') }}</span>
                    </div>

                    <!-- Sandbox Fast Payment Simulation -->
                    <button @click="simulatePayment()" type="button" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all cursor-pointer w-full flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <span>{{ __('messages.test_simulate_payment') }}</span>
                    </button>
                </div>

            </div>
        </div>
    @endif

</div>
@endsection
