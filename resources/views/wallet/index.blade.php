@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-8"
     x-data="{
         showQrModal: {{ isset($vietQrModal) && $vietQrModal ? 'true' : 'false' }},
         rawAmount: 200000,
         copiedMsg: '',
         setAmount(val) {
             this.rawAmount = val;
         },
         updateCustomAmount(e) {
             let val = e.target.value.replace(/\D/g, '');
             if (!val || val === '') val = '0';
             this.rawAmount = parseInt(val, 10);
         },
         formatNumber(num) {
             return new Intl.NumberFormat('vi-VN').format(num || 0);
         },
         copyToClipboard(text) {
             navigator.clipboard.writeText(text);
             this.copiedMsg = '{{ __('messages.copied_to_clipboard') }}';
             setTimeout(() => this.copiedMsg = '', 2000);
         }
     }">

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('messages.my_wallet') }}</h1>
            <p class="text-sm text-slate-500 font-medium mt-1">{{ __('messages.wallet_description') }}</p>
        </div>
    </div>

    <!-- Balance Overview & Top-Up Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
        
        <!-- Balance Card (Consistent Light/Brand Accent Theme) -->
        <div class="bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 text-white rounded-2xl p-6 shadow-md shadow-orange-500/10 space-y-6 relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-extrabold uppercase tracking-wider text-orange-100">{{ __('messages.current_balance') }}</span>
                <div class="w-9 h-9 rounded-xl bg-white/20 text-white flex items-center justify-center font-black text-xs backdrop-blur-xs">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <div>
                <p class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    {{ number_format($user->balance, 0, ',', '.') }} <span class="text-lg font-bold text-orange-100">đ</span>
                </p>
            </div>
        </div>

        <!-- Top-Up Form -->
        <div class="md:col-span-2 bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-6">
            <h3 class="text-base font-extrabold text-slate-900 border-b border-slate-100 pb-3">
                {{ __('messages.topup_wallet_title') }}
            </h3>

            <form action="{{ route('wallet.topup') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Preset Buttons -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-3">
                        {{ __('messages.select_topup_preset') }}
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <button type="button" @click="setAmount(100000)"
                                :class="rawAmount === 100000 ? 'border-orange-500 bg-orange-50 text-orange-600 font-black ring-2 ring-orange-500/20' : 'border-slate-200 text-slate-700 hover:border-slate-300 bg-white'"
                                class="p-3 rounded-xl border text-xs font-bold transition-all text-center cursor-pointer">
                            100.000 đ
                        </button>
                        <button type="button" @click="setAmount(200000)"
                                :class="rawAmount === 200000 ? 'border-orange-500 bg-orange-50 text-orange-600 font-black ring-2 ring-orange-500/20' : 'border-slate-200 text-slate-700 hover:border-slate-300 bg-white'"
                                class="p-3 rounded-xl border text-xs font-bold transition-all text-center cursor-pointer">
                            200.000 đ
                        </button>
                        <button type="button" @click="setAmount(500000)"
                                :class="rawAmount === 500000 ? 'border-orange-500 bg-orange-50 text-orange-600 font-black ring-2 ring-orange-500/20' : 'border-slate-200 text-slate-700 hover:border-slate-300 bg-white'"
                                class="p-3 rounded-xl border text-xs font-bold transition-all text-center cursor-pointer">
                            500.000 đ
                        </button>
                        <button type="button" @click="setAmount(1000000)"
                                :class="rawAmount === 1000000 ? 'border-orange-500 bg-orange-50 text-orange-600 font-black ring-2 ring-orange-500/20' : 'border-slate-200 text-slate-700 hover:border-slate-300 bg-white'"
                                class="p-3 rounded-xl border text-xs font-bold transition-all text-center cursor-pointer">
                            1.000.000 đ
                        </button>
                    </div>
                </div>

                <!-- Customized Amount Input with Currency Suffix -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        {{ __('messages.custom_amount_label') }}
                    </label>
                    <div class="relative flex items-center">
                        <input type="text"
                               :value="formatNumber(rawAmount)"
                               @input="updateCustomAmount($event)"
                               class="input-field py-3 pl-4 pr-16 font-mono text-base font-extrabold text-slate-900 focus:ring-2 focus:ring-orange-500/20"
                               placeholder="100.000"
                               required>
                        <input type="hidden" name="amount" :value="rawAmount">
                        <div class="absolute right-4 text-sm font-black text-slate-400 pointer-events-none">
                            VNĐ
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-extrabold text-sm flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>{{ __('messages.proceed_topup_vietqr') }}</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Top-Up History Table -->
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs space-y-4">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-900">{{ __('messages.topup_history_title') }}</h3>
        </div>

        @if($topupOrders->isEmpty())
            <div class="p-8 text-center text-xs text-slate-400 font-medium">
                {{ __('messages.no_topup_history') }}
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-semibold text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-6 py-4">{{ __('messages.order_number') }}</th>
                            <th class="px-6 py-4">{{ __('messages.order_date') }}</th>
                            <th class="px-6 py-4">{{ __('messages.order_status') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('messages.order_total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($topupOrders as $topup)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 font-black text-slate-900">{{ $topup->order_number }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $topup->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    @if($topup->status === 'paid')
                                        <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-extrabold">
                                            {{ __('messages.topup_status_credited') }}
                                        </span>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-extrabold">
                                                {{ __('messages.status_pending') }}
                                            </span>
                                            <a href="{{ route('wallet.topup.show', $topup->id) }}" class="px-3 py-1 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-[11px] rounded-xl shadow-2xs transition-colors shrink-0">
                                                {{ __('messages.pay_now_qr') }}
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-black text-emerald-600">
                                    +{{ number_format($topup->total_amount, 0, ',', '.') }} VNĐ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($topupOrders->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $topupOrders->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Transaction History (Balance Ledger) -->
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs space-y-4">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-base font-extrabold text-slate-900">{{ __('messages.balance_history_title') }}</h3>
            <p class="text-xs text-slate-500 font-medium mt-1">{{ __('messages.balance_history_desc') }}</p>
        </div>

        @if($transactions->isEmpty())
            <div class="p-8 text-center text-xs text-slate-400 font-medium">
                {{ __('messages.no_balance_history') }}
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-semibold text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 font-extrabold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="px-6 py-4">{{ __('messages.tx_date') }}</th>
                            <th class="px-6 py-4">{{ __('messages.tx_action') }}</th>
                            <th class="px-6 py-4">{{ __('messages.tx_description') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('messages.tx_amount') }}</th>
                            <th class="px-6 py-4 text-right">{{ __('messages.tx_balance_after') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold
                                        {{ $tx->type === 'in' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $tx->action_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate">{{ $tx->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-right font-black whitespace-nowrap {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900 whitespace-nowrap">
                                    {{ number_format($tx->balance_after, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($transactions->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>

    <!-- Embedded VietQR Modal for TopUp -->
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

                     this.pollInterval = setInterval(async () => {
                         try {
                             const res = await fetch('/orders/{{ $order->id }}/status');
                             if (res.ok) {
                                 const data = await res.json();
                                 if (data.paid) {
                                     clearInterval(this.pollInterval);
                                     clearInterval(this.timerInterval);
                                     window.location.href = '{{ route('wallet.index') }}';
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
                             window.location.href = '{{ route('wallet.index') }}';
                         }
                     } catch (e) { console.error(e); }
                 }
             }"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">

            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 max-w-lg w-full shadow-2xl space-y-6 relative overflow-hidden">
                
                <div x-show="copiedMsg" x-transition class="absolute top-4 right-4 bg-emerald-600 text-white text-xs font-bold px-3 py-1.5 rounded-lg shadow-md z-10">
                    <span x-text="copiedMsg"></span>
                </div>

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

                <p class="text-xs text-slate-600 text-center font-medium leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                    {{ __('messages.scan_qr_instruction') }}
                </p>

                <div class="flex flex-col items-center justify-center space-y-3">
                    <div class="p-3 bg-white border border-slate-200 rounded-2xl shadow-sm relative group">
                        <img src="{{ $vietQrData['qr_url'] }}" alt="VietQR Code" class="w-56 h-56 object-contain rounded-xl">
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                        <span>{{ __('messages.countdown_timer') }}:</span>
                        <span class="text-orange-600 font-mono text-sm font-extrabold" x-text="formatTime(timeLeft)"></span>
                    </div>
                </div>

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

                <div class="space-y-3 text-center">
                    <div class="flex items-center justify-center gap-2 text-xs font-semibold text-slate-500 animate-pulse">
                        <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                        <span>{{ __('messages.waiting_payment_auto_detect') }}</span>
                    </div>

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
