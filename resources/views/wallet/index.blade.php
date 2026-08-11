@extends('layouts.app')

@section('content')
    <!-- Page Header -->
    <div class="mb-8 flex items-center justify-between gap-4 md:mb-12">
        <div class="flex flex-col items-start text-left">
            <h1 class="text-xl font-black tracking-tighter text-slate-900 uppercase md:text-2xl">
                {{ __('messages.topup_wallet_title') }}
            </h1>
            <p class="mt-1 ml-4 hidden text-[10px] font-black tracking-[0.3em] text-slate-400 uppercase md:block">
                {{ __('messages.wallet_description') }}
            </p>
            <p class="mt-0.5 text-[8px] font-black tracking-widest text-slate-400 uppercase opacity-80 md:hidden">
                {{ Str::limit(__('messages.wallet_description'), 30) }}
            </p>
        </div>

        <div class="flex items-center gap-3">
            {{-- Balance Badge --}}
            <div class="flex items-center gap-2 rounded-2xl border border-orange-200 bg-orange-50 px-4 py-2.5 md:px-6 md:py-3">
                <span class="text-[9px] font-black tracking-widest text-orange-400 uppercase md:text-[10px]">{{ __('messages.current_balance') }}</span>
                <span class="text-sm font-black tracking-tighter text-orange-600 md:text-lg">{{ number_format($user->balance, 0, ',', '.') }} đ</span>
            </div>
        </div>
    </div>

    {{-- Invoice Creation Form + Guide (2-column) --}}
    <div class="mb-12 grid grid-cols-1 gap-8 lg:grid-cols-2"
         x-data="{
             showQrModal: {{ isset($vietQrModal) && $vietQrModal ? 'true' : 'false' }},
             copiedMsg: '',
             copyToClipboard(text) {
                 navigator.clipboard.writeText(text);
                 this.copiedMsg = '{{ __('messages.copied_to_clipboard') }}';
                 setTimeout(() => this.copiedMsg = '', 2000);
             }
         }">

        {{-- Left: Top-up Form --}}
        <form action="{{ route('wallet.topup') }}" method="POST"
              class="relative space-y-6 overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/20 md:space-y-8 md:p-8">
            @csrf

            <div class="absolute top-0 right-0 h-32 w-32 rounded-full bg-orange-500/5 blur-3xl"></div>

            <div class="relative z-10 space-y-1">
                <h3 class="text-lg font-black tracking-tighter text-slate-900 uppercase">
                    {{ __('messages.topup_wallet_title') }}
                </h3>
                <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase md:text-xs">{{ __('messages.topup_guide_subtitle') }}</p>
            </div>

            <div class="relative z-10 space-y-5">
                {{-- Preset Amount Buttons --}}
                <div>
                    <label class="ml-4 block text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-3">
                        {{ __('messages.select_topup_preset') }}
                    </label>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach([100000, 200000, 500000, 1000000] as $preset)
                            <button type="button"
                                    onclick="selectPreset({{ $preset }}, this)"
                                    class="preset-btn group relative cursor-pointer rounded-2xl border border-slate-200 bg-white p-3 text-center text-xs font-black tracking-tight text-slate-700 shadow-sm transition-all hover:border-orange-300 active:scale-95 {{ $loop->index === 1 ? 'border-orange-500 ring-2 ring-orange-500/20 text-orange-600 bg-orange-50' : '' }}">
                                {{-- Active indicator --}}
                                <div class="active-indicator absolute top-2 right-2 flex h-4 w-4 items-center justify-center rounded-full bg-orange-500 text-[8px] text-white transition-transform {{ $loop->index === 1 ? 'scale-100' : 'scale-0' }}">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                </div>
                                {{ number_format($preset, 0, ',', '.') }} đ
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Custom Amount Input --}}
                <div>
                    <label class="ml-4 block text-[10px] font-bold tracking-widest text-slate-400 uppercase mb-2">
                        {{ __('messages.custom_amount_label') }}
                    </label>
                    <input type="number"
                           name="amount"
                           id="topup-amount"
                           value="200000"
                           min="10000"
                           max="50000000"
                           placeholder="VD: 200000"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-6 py-4 text-sm font-black text-slate-900 shadow-inner transition-all focus:border-orange-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                           required>
                    <div class="mt-2 rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-2">
                        <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase">
                            {{ __('messages.amount_min_hint', ['amount' => '10.000đ']) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Info Box --}}
            <div class="relative z-10 flex items-start gap-4 rounded-2xl border border-amber-500/20 bg-amber-500/5 p-4 md:p-5">
                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-[10px] leading-relaxed font-bold tracking-tight text-amber-700 uppercase md:text-xs">
                    {{ __('messages.auto_credit_notice') }}
                </p>
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                    class="btn-primary relative z-10 w-full rounded-xl py-4 text-[11px] font-black tracking-widest uppercase shadow-lg shadow-orange-500/20 transition-all hover:scale-[1.01] active:scale-95 cursor-pointer flex items-center justify-center gap-2 md:py-5">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                {{ __('messages.proceed_topup_vietqr') }}
            </button>
        </form>

        {{-- Right: How to Top-Up Guide --}}
        <div class="relative space-y-6 overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/20 md:space-y-8 md:p-8">
            <div class="absolute top-0 left-0 h-32 w-32 rounded-full bg-emerald-500/5 blur-3xl"></div>

            <div class="relative z-10 space-y-1">
                <h3 class="text-lg font-black tracking-tighter text-slate-900 uppercase">
                    {{ __('messages.topup_guide_title') }}
                </h3>
                <p class="text-[10px] font-bold tracking-widest text-slate-400 uppercase md:text-xs">{{ __('messages.topup_guide_subtitle') }}</p>
            </div>

            <div class="relative z-10 space-y-4">
                @php
                    $steps = [
                        ['title' => __('messages.topup_step_1_title'), 'desc' => __('messages.topup_step_1_desc')],
                        ['title' => __('messages.topup_step_2_title'), 'desc' => __('messages.topup_step_2_desc')],
                        ['title' => __('messages.topup_step_3_title'), 'desc' => __('messages.topup_step_3_desc')],
                    ];
                @endphp

                @foreach($steps as $index => $step)
                    <div class="group flex items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50/50 p-4">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-orange-100 text-sm font-black text-orange-600">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <p class="text-xs font-black tracking-tighter text-slate-900 uppercase">
                                {{ $step['title'] }}
                            </p>
                            <p class="mt-1 text-[11px] leading-relaxed font-bold text-slate-400">
                                {{ $step['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Auto-credit notice --}}
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-500/20 bg-emerald-500/5 p-3 md:p-4">
                <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-xs leading-relaxed font-bold tracking-tight text-emerald-700 uppercase">
                    {{ __('messages.auto_credit_notice') }}
                </p>
            </div>
        </div>

        {{-- VietQR Modal (shared component) --}}
        @if(isset($vietQrModal) && $vietQrModal && isset($vietQrData))
            <x-vietqr-modal :order="$order" :viet-qr-data="$vietQrData" :redirect-url="route('wallet.index')" />
        @endif
    </div>

    {{-- Top-Up History Table --}}
    <div class="mt-10">
        <div class="relative z-10 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/20">
            {{-- Table Header --}}
            <div class="flex flex-col justify-between gap-6 border-b border-slate-100 bg-slate-50/50 p-6 md:flex-row md:items-center">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100 text-lg text-orange-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black tracking-tighter text-slate-900 uppercase">
                            {{ __('messages.topup_history_title') }}
                        </h3>
                        <p class="mt-1 text-[9px] font-bold tracking-widest text-slate-400 uppercase">{{ __('messages.wallet_description') }}</p>
                    </div>
                </div>
            </div>

            {{-- Mobile Card List --}}
            <div class="divide-y divide-slate-100/90 md:hidden">
                @forelse($topupOrders as $topup)
                    <div class="space-y-3 p-5">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                @if($topup->status === 'paid')
                                    <span class="text-[11px] font-black italic px-3 py-1.5 rounded-lg border uppercase tracking-widest whitespace-nowrap bg-emerald-50 text-emerald-700 border-emerald-200">
                                        {{ __('messages.topup_status_credited') }}
                                    </span>
                                @else
                                    <span class="text-[11px] font-black italic px-3 py-1.5 rounded-lg border uppercase tracking-widest whitespace-nowrap bg-amber-50 text-amber-700 border-amber-200">
                                        {{ __('messages.status_pending') }}
                                    </span>
                                @endif
                                <p class="mt-2 text-[13px] leading-tight font-bold text-slate-700 italic">
                                    {{ __('messages.vietqr_sepay_payment') }}
                                </p>
                            </div>
                            <div class="flex shrink-0 flex-col items-end">
                                <span class="text-[13px] font-bold text-slate-900 italic">{{ $topup->created_at->format('d/m/Y') }}</span>
                                <span class="text-[11px] font-medium text-slate-400">{{ $topup->created_at->format('H:i:s') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-2 border-t border-dashed border-slate-100 pt-3">
                            <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                #{{ $topup->order_number }}
                            </span>
                            <div class="flex items-center gap-3">
                                <span class="text-[13px] font-black tracking-tighter whitespace-nowrap text-slate-600 italic">
                                    {{ number_format($topup->total_amount, 0, ',', '.') }} đ
                                </span>
                                @if($topup->status !== 'paid')
                                    <a href="{{ route('wallet.topup.show', $topup->id) }}"
                                       class="hover:bg-orange-500 inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all hover:text-white"
                                       title="{{ __('messages.pay_now_qr') }}">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center gap-5 px-10 py-24 text-center opacity-40 transition-opacity duration-500 hover:opacity-100">
                        <div class="flex h-20 w-20 items-center justify-center rounded-[28px] border border-slate-200 bg-slate-100 text-slate-300 shadow-inner">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[12px] font-black tracking-tighter text-slate-500 uppercase">{{ __('messages.no_topup_history') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Desktop Table --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[700px] border-collapse text-left">
                    <thead class="border-b border-dashed border-slate-200">
                        <tr>
                            <th class="px-8 py-6 text-[10px] font-black tracking-[0.15em] text-slate-400 uppercase">{{ __('messages.order_number') }}</th>
                            <th class="px-8 py-6 text-center text-[10px] font-black tracking-[0.15em] text-slate-400 uppercase">{{ __('messages.order_status') }}</th>
                            <th class="px-8 py-6 text-[10px] font-black tracking-[0.15em] whitespace-nowrap text-slate-400 uppercase">{{ __('messages.order_total') }}</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black tracking-[0.15em] whitespace-nowrap text-slate-400 uppercase">{{ __('messages.order_date') }}</th>
                            <th class="px-8 py-6 text-center text-[10px] font-black tracking-[0.15em] whitespace-nowrap text-slate-400 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/90">
                        @forelse($topupOrders as $topup)
                            <tr class="group/row transition-all duration-300 hover:bg-slate-50/80">
                                <td class="relative px-8 py-6">
                                    <div class="bg-orange-500 absolute top-1/2 left-0 h-0 w-1 -translate-y-1/2 rounded-r-full opacity-0 transition-all duration-300 group-hover/row:h-3/4 group-hover/row:opacity-100"></div>
                                    <span class="group-hover/row:text-orange-600 text-[13px] font-black whitespace-nowrap text-slate-400 uppercase">
                                        #{{ $topup->order_number }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($topup->status === 'paid')
                                        <span class="text-[11px] font-black italic px-3 py-1.5 rounded-lg border uppercase tracking-widest whitespace-nowrap bg-emerald-50 text-emerald-700 border-emerald-200">
                                            {{ __('messages.topup_status_credited') }}
                                        </span>
                                    @else
                                        <span class="text-[11px] font-black italic px-3 py-1.5 rounded-lg border uppercase tracking-widest whitespace-nowrap bg-amber-50 text-amber-700 border-amber-200">
                                            {{ __('messages.status_pending') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-[13px] font-black tracking-tighter whitespace-nowrap text-slate-600 italic">
                                    {{ number_format($topup->total_amount, 0, ',', '.') }} đ
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <span class="text-[13px] font-bold tracking-widest whitespace-nowrap text-slate-400 uppercase">
                                        {{ $topup->created_at->format('H:i d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($topup->status !== 'paid')
                                        <a href="{{ route('wallet.topup.show', $topup->id) }}"
                                           class="hover:bg-orange-500 inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 text-slate-400 transition-all hover:text-white"
                                           title="{{ __('messages.pay_now_qr') }}">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-10 py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-5 pb-6 opacity-40 transition-opacity duration-500 hover:opacity-100">
                                        <div class="flex h-20 w-20 items-center justify-center rounded-[28px] border border-slate-200 bg-slate-100 text-slate-300 shadow-inner">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[12px] font-black tracking-tighter text-slate-500 uppercase">{{ __('messages.no_topup_history') }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($topupOrders->hasPages())
                <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-100 bg-slate-50/50 p-6 sm:flex-row">
                    <p class="w-full text-center text-[10px] font-bold tracking-widest text-slate-400 uppercase sm:w-auto sm:text-left">
                        {{ $topupOrders->firstItem() }} - {{ $topupOrders->lastItem() }} / {{ $topupOrders->total() }}
                    </p>
                    <div class="w-full sm:w-auto">
                        {{ $topupOrders->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Balance History (Transaction Ledger) --}}
    <div class="mt-10">
        <div class="relative z-10 overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-xl shadow-slate-200/20">
            <div class="flex flex-col justify-between gap-6 border-b border-slate-100 bg-slate-50/50 p-6 md:flex-row md:items-center">
                <div class="flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-lg text-slate-500">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black tracking-tighter text-slate-900 uppercase">{{ __('messages.balance_history_title') }}</h3>
                        <p class="mt-1 text-[9px] font-bold tracking-widest text-slate-400 uppercase">{{ __('messages.balance_history_desc') }}</p>
                    </div>
                </div>
            </div>

            {{-- Mobile Card List --}}
            <div class="divide-y divide-slate-100/90 md:hidden">
                @forelse($transactions as $tx)
                    <div class="space-y-2 p-5">
                        <div class="flex items-start justify-between gap-2">
                            <span class="text-[11px] font-black italic px-3 py-1.5 rounded-lg border uppercase tracking-widest whitespace-nowrap {{ $tx->type === 'in' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                {{ $tx->action_label }}
                            </span>
                            <div class="flex shrink-0 flex-col items-end">
                                <span class="text-[13px] font-bold text-slate-900 italic">{{ $tx->created_at->format('d/m/Y') }}</span>
                                <span class="text-[11px] font-medium text-slate-400">{{ $tx->created_at->format('H:i:s') }}</span>
                            </div>
                        </div>
                        <p class="text-[11px] font-medium text-slate-500 truncate">{{ $tx->description ?? '-' }}</p>
                        <div class="flex items-center justify-between gap-2 border-t border-dashed border-slate-100 pt-3">
                            <span class="text-[10px] font-black tracking-widest text-slate-400 uppercase">
                                {{ __('messages.tx_balance_after') }}: {{ number_format($tx->balance_after, 0, ',', '.') }} đ
                            </span>
                            <span class="text-[13px] font-black tracking-tighter whitespace-nowrap italic {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center gap-5 px-10 py-24 text-center opacity-40 transition-opacity duration-500 hover:opacity-100">
                        <div class="flex h-20 w-20 items-center justify-center rounded-[28px] border border-slate-200 bg-slate-100 text-slate-300 shadow-inner">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[12px] font-black tracking-tighter text-slate-500 uppercase">{{ __('messages.no_balance_history') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Desktop Table --}}
            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[700px] border-collapse text-left">
                    <thead class="border-b border-dashed border-slate-200">
                        <tr>
                            <th class="px-8 py-6 text-[10px] font-black tracking-[0.15em] text-slate-400 uppercase">{{ __('messages.tx_date') }}</th>
                            <th class="px-8 py-6 text-center text-[10px] font-black tracking-[0.15em] text-slate-400 uppercase">{{ __('messages.tx_action') }}</th>
                            <th class="px-8 py-6 text-[10px] font-black tracking-[0.15em] text-slate-400 uppercase">{{ __('messages.tx_description') }}</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black tracking-[0.15em] whitespace-nowrap text-slate-400 uppercase">{{ __('messages.tx_amount') }}</th>
                            <th class="px-8 py-6 text-right text-[10px] font-black tracking-[0.15em] whitespace-nowrap text-slate-400 uppercase">{{ __('messages.tx_balance_after') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/90">
                        @forelse($transactions as $tx)
                            <tr class="group/row transition-all duration-300 hover:bg-slate-50/80">
                                <td class="relative px-8 py-6">
                                    <div class="bg-orange-500 absolute top-1/2 left-0 h-0 w-1 -translate-y-1/2 rounded-r-full opacity-0 transition-all duration-300 group-hover/row:h-3/4 group-hover/row:opacity-100"></div>
                                    <span class="text-[13px] font-bold tracking-widest whitespace-nowrap text-slate-400 uppercase">
                                        {{ $tx->created_at->format('H:i d/m/Y') }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="text-[11px] font-black italic px-3 py-1.5 rounded-lg border uppercase tracking-widest whitespace-nowrap {{ $tx->type === 'in' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                        {{ $tx->action_label }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-[13px] leading-tight font-bold text-slate-700 italic max-w-xs truncate">
                                    {{ $tx->description ?? '-' }}
                                </td>
                                <td class="px-8 py-6 text-right text-[13px] font-black tracking-tighter whitespace-nowrap italic {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
                                </td>
                                <td class="px-8 py-6 text-right text-[13px] font-black tracking-tighter whitespace-nowrap text-slate-900 italic">
                                    {{ number_format($tx->balance_after, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-10 py-24 text-center">
                                    <div class="flex flex-col items-center justify-center gap-5 pb-6 opacity-40 transition-opacity duration-500 hover:opacity-100">
                                        <div class="flex h-20 w-20 items-center justify-center rounded-[28px] border border-slate-200 bg-slate-100 text-slate-300 shadow-inner">
                                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                        </div>
                                        <div class="space-y-1">
                                            <p class="text-[12px] font-black tracking-tighter text-slate-500 uppercase">{{ __('messages.no_balance_history') }}</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-100 bg-slate-50/50 p-6 sm:flex-row">
                    <p class="w-full text-center text-[10px] font-bold tracking-widest text-slate-400 uppercase sm:w-auto sm:text-left">
                        {{ $transactions->firstItem() }} - {{ $transactions->lastItem() }} / {{ $transactions->total() }}
                    </p>
                    <div class="w-full sm:w-auto">
                        {{ $transactions->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function selectPreset(amount, button) {
            // Reset all preset buttons
            document.querySelectorAll('.preset-btn').forEach(btn => {
                btn.classList.remove('border-orange-500', 'ring-2', 'ring-orange-500/20', 'text-orange-600', 'bg-orange-50');
                btn.classList.add('border-slate-200', 'text-slate-700', 'bg-white');
                const ind = btn.querySelector('.active-indicator');
                if (ind) { ind.classList.remove('scale-100'); ind.classList.add('scale-0'); }
            });

            // Activate clicked button
            button.classList.remove('border-slate-200', 'text-slate-700', 'bg-white');
            button.classList.add('border-orange-500', 'ring-2', 'ring-orange-500/20', 'text-orange-600', 'bg-orange-50');
            const activeInd = button.querySelector('.active-indicator');
            if (activeInd) { activeInd.classList.remove('scale-0'); activeInd.classList.add('scale-100'); }

            // Update hidden input
            document.getElementById('topup-amount').value = amount;
        }
    </script>
@endpush
