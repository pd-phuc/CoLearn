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

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ __('messages.my_wallet') }}</h1>
        <p class="text-xs sm:text-sm text-slate-400 font-bold uppercase tracking-wider mt-1">{{ __('messages.wallet_description') }}</p>
    </div>

    {{-- Balance + Top-Up Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Balance Card --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-orange-500 via-orange-600 to-amber-600 text-white rounded-3xl p-6 shadow-xl shadow-orange-500/15 space-y-5">
            <div class="absolute top-0 right-0 h-32 w-32 rounded-full bg-white/5 blur-3xl"></div>

            <div class="relative z-10">
                <span class="text-[10px] font-black uppercase tracking-widest text-orange-100">{{ __('messages.current_balance') }}</span>
                <p class="text-3xl sm:text-4xl font-black tracking-tighter text-white mt-2">
                    {{ number_format($user->balance, 0, ',', '.') }} <span class="text-lg font-bold text-orange-200">đ</span>
                </p>
            </div>

            <div class="relative z-10 flex items-center gap-4 border-t border-white/15 pt-4">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-widest text-orange-200">{{ __('messages.total_deposited') }}</span>
                    <p class="text-sm font-black text-white/90 mt-0.5">
                        {{ number_format($user->total_deposit ?? 0, 0, ',', '.') }} đ
                    </p>
                </div>
            </div>
        </div>

        {{-- Top-Up Form --}}
        <div class="lg:col-span-2 bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <div class="w-9 h-9 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black text-xs">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ __('messages.topup_wallet_title') }}</h3>
                        <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">{{ __('messages.wallet_quick_topup') }}</p>
                    </div>
                </div>

                <form action="{{ route('wallet.topup') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Preset Buttons --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">
                            {{ __('messages.select_topup_preset') }}
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @foreach([100000, 200000, 500000, 1000000] as $preset)
                                <button type="button" @click="setAmount({{ $preset }})"
                                        :class="rawAmount === {{ $preset }} ? 'border-orange-500 bg-orange-50 text-orange-600 ring-2 ring-orange-500/20' : 'border-slate-200 text-slate-700 hover:border-slate-300 bg-white'"
                                        class="group relative p-3.5 rounded-xl border text-xs font-black transition-all text-center cursor-pointer active:scale-95">
                                    {{-- Active indicator --}}
                                    <div :class="rawAmount === {{ $preset }} ? 'scale-100' : 'scale-0'"
                                         class="absolute top-2 right-2 flex h-4 w-4 items-center justify-center rounded-full bg-orange-500 text-[8px] text-white transition-transform">
                                        <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                    </div>
                                    {{ number_format($preset, 0, ',', '.') }} đ
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Custom Amount Input --}}
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">
                            {{ __('messages.custom_amount_label') }}
                        </label>
                        <div class="relative flex items-center">
                            <input type="text"
                                   :value="formatNumber(rawAmount)"
                                   @input="updateCustomAmount($event)"
                                   class="input-field py-3.5 pl-4 pr-16 font-mono text-base font-black text-slate-900 focus:ring-2 focus:ring-orange-500/20 rounded-xl"
                                   placeholder="100.000"
                                   required>
                            <input type="hidden" name="amount" :value="rawAmount">
                            <div class="absolute right-4 text-sm font-black text-slate-300 pointer-events-none uppercase">
                                VNĐ
                            </div>
                        </div>
                        <div class="mt-2 rounded-lg border border-slate-100 bg-slate-50/50 px-3 py-1.5">
                            <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">
                                {{ __('messages.amount_min_hint', ['amount' => '10.000đ']) }}
                            </p>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-3.5 rounded-xl font-black text-sm flex items-center justify-center gap-2 shadow-lg shadow-orange-500/20 cursor-pointer uppercase tracking-wider active:scale-[0.98] transition-transform">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>{{ __('messages.proceed_topup_vietqr') }}</span>
                    </button>
                </form>
            </div>

            {{-- How to Top-Up Guide (mst-shoproblox style) --}}
            <div class="border-t border-slate-100 bg-slate-50/30 p-6 space-y-3">
                <div class="flex items-center gap-2 mb-4">
                    <div class="bg-orange-500 h-5 w-1 rounded-full"></div>
                    <div>
                        <p class="text-[10px] font-black tracking-widest text-slate-900 uppercase">{{ __('messages.topup_guide_title') }}</p>
                        <p class="text-[9px] font-bold tracking-widest text-slate-400 uppercase">{{ __('messages.topup_guide_subtitle') }}</p>
                    </div>
                </div>

                @php
                    $topupSteps = [
                        ['title' => __('messages.topup_step_1_title'), 'desc' => __('messages.topup_step_1_desc')],
                        ['title' => __('messages.topup_step_2_title'), 'desc' => __('messages.topup_step_2_desc')],
                        ['title' => __('messages.topup_step_3_title'), 'desc' => __('messages.topup_step_3_desc')],
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($topupSteps as $index => $step)
                        <div class="flex items-start gap-3 rounded-xl border border-slate-200/80 bg-white p-3">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-orange-600 text-xs font-black">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-[11px] font-black tracking-tight text-slate-900 uppercase">{{ $step['title'] }}</p>
                                <p class="text-[10px] font-medium text-slate-400 mt-0.5 leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Top-Up History Table --}}
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="flex items-center gap-3 p-6 border-b border-slate-100">
            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ __('messages.topup_history_title') }}</h3>
                <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">{{ __('messages.wallet_description') }}</p>
            </div>
        </div>

        @if($topupOrders->isEmpty())
            <div class="flex flex-col items-center justify-center gap-4 px-10 py-16 opacity-40 hover:opacity-100 transition-opacity">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-300 shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
                <div class="space-y-1 text-center">
                    <p class="text-xs font-black tracking-tighter text-slate-500 uppercase">{{ __('messages.no_topup_history') }}</p>
                </div>
            </div>
        @else
            {{-- Mobile Card List --}}
            <div class="divide-y divide-slate-100 md:hidden">
                @foreach($topupOrders as $topup)
                    <div class="p-4 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                @if($topup->status === 'paid')
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black uppercase tracking-wider">
                                        {{ __('messages.topup_status_credited') }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-black uppercase tracking-wider">
                                        {{ __('messages.status_pending') }}
                                    </span>
                                @endif
                                <p class="text-[10px] font-black tracking-widest text-slate-400 uppercase mt-2">#{{ $topup->order_number }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-sm font-black tracking-tighter text-emerald-600">+{{ number_format($topup->total_amount, 0, ',', '.') }} đ</span>
                                <p class="text-[10px] font-medium text-slate-400 mt-0.5">{{ $topup->created_at->format('H:i d/m/Y') }}</p>
                            </div>
                        </div>
                        @if($topup->status !== 'paid')
                            <a href="{{ route('wallet.topup.show', $topup->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white font-black text-[10px] rounded-lg shadow-xs transition-colors uppercase tracking-wider">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                {{ __('messages.pay_now_qr') }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-dashed border-slate-200">
                        <tr>
                            <th class="px-6 py-5 text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.order_number') }}</th>
                            <th class="px-6 py-5 text-center text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.order_status') }}</th>
                            <th class="px-6 py-5 text-right text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.order_total') }}</th>
                            <th class="px-6 py-5 text-right text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.order_date') }}</th>
                            <th class="px-6 py-5 text-center text-[10px] font-black tracking-widest text-slate-400 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($topupOrders as $topup)
                            <tr class="group hover:bg-slate-50/80 transition-all">
                                <td class="relative px-6 py-5">
                                    <div class="bg-orange-500 absolute top-1/2 left-0 h-0 w-1 -translate-y-1/2 rounded-r-full opacity-0 transition-all duration-300 group-hover:h-3/4 group-hover:opacity-100"></div>
                                    <span class="group-hover:text-orange-600 text-xs font-black text-slate-400 uppercase tracking-wide transition-colors">#{{ $topup->order_number }}</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($topup->status === 'paid')
                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-black uppercase tracking-wider">
                                            {{ __('messages.topup_status_credited') }}
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 text-[10px] font-black uppercase tracking-wider">
                                            {{ __('messages.status_pending') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-right font-black text-emerald-600 text-sm tracking-tighter">
                                    +{{ number_format($topup->total_amount, 0, ',', '.') }} đ
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <span class="text-xs font-bold tracking-wide text-slate-400">{{ $topup->created_at->format('H:i d/m/Y') }}</span>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($topup->status !== 'paid')
                                        <a href="{{ route('wallet.topup.show', $topup->id) }}"
                                           class="hover:bg-orange-500 hover:text-white inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all"
                                           title="{{ __('messages.pay_now_qr') }}">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($topupOrders->hasPages())
                <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 p-4 sm:p-6">
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">
                        {{ $topupOrders->firstItem() }} - {{ $topupOrders->lastItem() }} / {{ $topupOrders->total() }}
                    </p>
                    <div>{{ $topupOrders->links() }}</div>
                </div>
            @endif
        @endif
    </div>

    {{-- Transaction History (Balance Ledger) --}}
    <div class="bg-white border border-slate-200/80 rounded-3xl overflow-hidden shadow-xs">
        <div class="flex items-center gap-3 p-6 border-b border-slate-100">
            <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ __('messages.balance_history_title') }}</h3>
                <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">{{ __('messages.balance_history_desc') }}</p>
            </div>
        </div>

        @if($transactions->isEmpty())
            <div class="flex flex-col items-center justify-center gap-4 px-10 py-16 opacity-40 hover:opacity-100 transition-opacity">
                <div class="flex h-16 w-16 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-300 shadow-inner">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                </div>
                <div class="space-y-1 text-center">
                    <p class="text-xs font-black tracking-tighter text-slate-500 uppercase">{{ __('messages.no_balance_history') }}</p>
                </div>
            </div>
        @else
            {{-- Mobile Card List --}}
            <div class="divide-y divide-slate-100 md:hidden">
                @foreach($transactions as $tx)
                    <div class="p-4 space-y-1">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider
                                {{ $tx->type === 'in' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                {{ $tx->action_label }}
                            </span>
                            <span class="font-black text-sm {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
                            </span>
                        </div>
                        <p class="text-[11px] font-medium text-slate-500 truncate">{{ $tx->description ?? '-' }}</p>
                        <div class="flex items-center justify-between text-[10px] font-medium text-slate-400">
                            <span>{{ $tx->created_at->format('H:i d/m/Y') }}</span>
                            <span class="font-bold text-slate-600">{{ __('messages.tx_balance_after') }}: {{ number_format($tx->balance_after, 0, ',', '.') }} đ</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-dashed border-slate-200">
                        <tr>
                            <th class="px-6 py-5 text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.tx_date') }}</th>
                            <th class="px-6 py-5 text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.tx_action') }}</th>
                            <th class="px-6 py-5 text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.tx_description') }}</th>
                            <th class="px-6 py-5 text-right text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.tx_amount') }}</th>
                            <th class="px-6 py-5 text-right text-[10px] font-black tracking-widest text-slate-400 uppercase">{{ __('messages.tx_balance_after') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($transactions as $tx)
                            <tr class="group hover:bg-slate-50/80 transition-all">
                                <td class="px-6 py-5 text-slate-400 whitespace-nowrap font-bold">{{ $tx->created_at->format('H:i d/m/Y') }}</td>
                                <td class="px-6 py-5">
                                    <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider
                                        {{ $tx->type === 'in' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                                        {{ $tx->action_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-slate-500 max-w-xs truncate font-medium">{{ $tx->description ?? '-' }}</td>
                                <td class="px-6 py-5 text-right font-black whitespace-nowrap text-sm tracking-tighter {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
                                </td>
                                <td class="px-6 py-5 text-right font-black text-slate-900 whitespace-nowrap">
                                    {{ number_format($tx->balance_after, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="flex items-center justify-between border-t border-slate-100 bg-slate-50/50 p-4 sm:p-6">
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">
                        {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} / {{ $transactions->total() }}
                    </p>
                    <div>{{ $transactions->links() }}</div>
                </div>
            @endif
        @endif
    </div>

    {{-- VietQR Modal (shared component) --}}
    @if(isset($vietQrModal) && $vietQrModal && isset($vietQrData))
        <x-vietqr-modal :order="$order" :viet-qr-data="$vietQrData" :redirect-url="route('wallet.index')" />
    @endif

</div>
@endsection
