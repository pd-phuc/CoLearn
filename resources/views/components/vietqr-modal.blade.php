{{-- VietQR Payment Modal — Shared component for checkout & wallet topup --}}
@props([
    'order',
    'vietQrData',
    'redirectUrl' => null,
])

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

             this.pollInterval = setInterval(async () => {
                 try {
                     const res = await fetch('/orders/{{ $order->id }}/status');
                     if (res.ok) {
                         const data = await res.json();
                         if (data.paid) {
                             clearInterval(this.pollInterval);
                             clearInterval(this.timerInterval);
                             window.location.href = data.redirect || '{{ $redirectUrl ?? route("wallet.index") }}';
                         }
                     }
                 } catch (e) { console.error(e); }
             }, 3000);
         }
     }"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">

    <div x-transition:enter="transition ease-out duration-300 delay-100"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="bg-white border border-slate-200/80 rounded-3xl max-w-3xl w-full shadow-2xl relative overflow-hidden">

        {{-- Toast Notification --}}
        <div x-show="copiedMsg" x-transition class="absolute top-4 right-14 bg-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md z-10">
            <span x-text="copiedMsg"></span>
        </div>

        {{-- Modal Header --}}
        <div class="flex items-center justify-between border-b border-slate-100 p-5 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black text-sm">
                    QR
                </div>
                <div>
                    <h3 class="text-sm sm:text-base font-black text-slate-900 uppercase tracking-tight">{{ __('messages.scan_vietqr_to_pay') }}</h3>
                    <p class="text-[10px] sm:text-xs text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ __('messages.order_number') }} {{ $vietQrData['order_number'] }}</p>
                </div>
            </div>
            <button @click="showQrModal = false" class="text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl p-2 transition-all cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- 2-Column Body: QR + Bank Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-5 sm:p-6">

            {{-- Left: QR Code --}}
            <div class="flex flex-col items-center justify-center space-y-4 bg-slate-50/80 rounded-2xl p-5 border border-slate-100 order-first">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ __('messages.scan_qr_instruction') }}</p>

                <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm group hover:shadow-md transition-shadow">
                    <img src="{{ $vietQrData['qr_url'] }}" alt="VietQR Code" class="w-44 h-44 sm:w-52 sm:h-52 object-contain rounded-xl group-hover:scale-[1.02] transition-transform">
                </div>

                {{-- Countdown --}}
                <div class="w-full bg-white border border-slate-200 rounded-xl p-3 text-center space-y-1">
                    <span class="text-[9px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.countdown_timer') }}</span>
                    <p class="text-2xl font-black tracking-tighter text-slate-900 font-mono" x-text="formatTime(timeLeft)"></p>
                </div>

                {{-- NAPAS Indicator --}}
                <div class="flex items-center gap-2 text-[10px] font-black tracking-widest text-emerald-600 uppercase">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    </span>
                    NAPAS 247 — {{ __('messages.auto_detect_active') }}
                </div>
            </div>

            {{-- Right: Bank Transfer Info --}}
            <div class="space-y-3 md:order-2">
                @php
                    $bankFields = [
                        ['key' => 'bank_name', 'label' => __('messages.bank_name_label'), 'copy' => false],
                        ['key' => 'account_name', 'label' => __('messages.account_name_label'), 'copy' => false],
                        ['key' => 'account_no', 'label' => __('messages.bank_account_no'), 'copy' => true],
                        ['key' => 'order_number', 'label' => __('messages.transfer_memo'), 'copy' => true],
                    ];
                @endphp

                @foreach($bankFields as $field)
                    <div class="group hover:border-orange-300 flex items-center justify-between rounded-xl border border-slate-200 bg-white p-3 transition-all">
                        <div class="flex flex-col min-w-0">
                            <span class="text-[9px] font-black tracking-widest text-slate-400 uppercase">{{ $field['label'] }}</span>
                            <span class="text-sm font-black tracking-tight text-slate-900 truncate {{ $field['key'] === 'order_number' ? 'font-mono' : '' }}">
                                {{ $vietQrData[$field['key']] }}
                            </span>
                        </div>
                        @if($field['copy'])
                            <button @click="copyToClipboard('{{ $vietQrData[$field['key']] }}')"
                                    class="hover:bg-orange-500 hover:text-white flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all ml-2"
                                    title="{{ __('messages.copy') }}">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>
                        @endif
                    </div>
                @endforeach

                {{-- Total Amount --}}
                <div class="flex items-center justify-between rounded-xl border-2 border-orange-200 bg-orange-50/50 p-3">
                    <span class="text-[9px] font-black tracking-widest text-orange-500 uppercase">{{ __('messages.total') }}</span>
                    <span class="text-lg font-black text-orange-600">{{ $vietQrData['formatted_amount'] }}</span>
                </div>

                {{-- Status Indicator --}}
                <div class="flex items-center justify-center gap-2 text-xs font-bold text-slate-500 py-2">
                    <div class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></div>
                    <span>{{ __('messages.waiting_payment_auto_detect') }}</span>
                </div>
            </div>
        </div>

        {{-- Bottom Info Bar --}}
        <div class="flex items-center gap-3 border-t border-slate-100 bg-emerald-50/50 px-5 sm:px-6 py-3">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <p class="text-[10px] sm:text-xs font-bold text-emerald-700">
                {{ __('messages.auto_credit_notice') }}
            </p>
        </div>
    </div>
</div>
