@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-3xl"
         x-data="{
             showQrModal: {{ isset($vietQrModal) && $vietQrModal ? 'true' : 'false' }},
             copiedMsg: '',
             copyToClipboard(text) {
                 navigator.clipboard.writeText(text);
                 this.copiedMsg = '{{ __('messages.copied_to_clipboard') }}';
                 setTimeout(() => this.copiedMsg = '', 2000);
             }
         }">

        {{-- Balance Card --}}
        <div class="mb-8 flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div>
                <p class="text-xs font-medium text-slate-500">{{ __('messages.current_balance') }}</p>
                <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($user->balance, 0, ',', '.') }} <span class="text-base font-medium text-slate-400">đ</span></p>
            </div>
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-50">
                <svg class="h-6 w-6 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
            </div>
        </div>

        {{-- Add Funds Form --}}
        <form action="{{ route('wallet.topup') }}" method="POST"
              class="mb-10 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <h2 class="text-lg font-bold text-slate-900">{{ __('messages.wallet_add_funds_title') }}</h2>

            {{-- Preset Amounts --}}
            <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach([100000, 200000, 500000, 1000000] as $preset)
                    <button type="button"
                            onclick="selectPreset({{ $preset }}, this)"
                            class="preset-btn relative cursor-pointer rounded-xl border-2 px-4 py-3 text-center text-sm font-semibold transition-all hover:border-orange-400 active:scale-95 {{ $loop->index === 1 ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-slate-200 bg-white text-slate-700 hover:bg-slate-50' }}">
                        {{ number_format($preset, 0, ',', '.') }} đ
                    </button>
                @endforeach
            </div>

            {{-- Custom Amount --}}
            <div class="mt-4">
                <label for="topup-amount" class="block text-sm font-medium text-slate-600">{{ __('messages.custom_amount_label') }}</label>
                <div class="relative mt-1.5">
                    <input type="number"
                           name="amount"
                           id="topup-amount"
                           value="200000"
                           min="10000"
                           max="50000000"
                           placeholder="200000"
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pr-14 text-sm font-medium text-slate-900 transition-colors focus:border-orange-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20"
                           required>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm font-medium text-slate-400">VNĐ</span>
                </div>
                <p class="mt-1.5 text-xs text-slate-400">{{ __('messages.amount_min_hint', ['amount' => '10.000đ']) }}</p>
            </div>

            {{-- Info --}}
            <div class="mt-4 flex items-start gap-2.5 rounded-xl bg-amber-50 p-3">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <p class="text-xs leading-relaxed text-amber-700">{{ __('messages.auto_credit_notice') }}</p>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="btn-primary mt-6 w-full rounded-xl py-3.5 text-sm font-semibold cursor-pointer">
                {{ __('messages.proceed_deposit_vietqr') }}
            </button>
        </form>

        {{-- VietQR Modal --}}
        @if(isset($vietQrModal) && $vietQrModal && isset($vietQrData))
            <x-vietqr-modal :order="$order" :viet-qr-data="$vietQrData" :redirect-url="route('wallet.index')" />
        @endif

        {{-- Deposit History --}}
        <div class="mb-10 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-sm font-bold text-slate-900">{{ __('messages.deposit_history_title') }}</h3>
            </div>

            {{-- Mobile Cards --}}
            <div class="divide-y divide-slate-100 md:hidden">
                @forelse($topupOrders as $topup)
                    <div class="flex items-center justify-between gap-3 px-5 py-4">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-900">{{ number_format($topup->total_amount, 0, ',', '.') }} đ</p>
                            <p class="mt-0.5 truncate text-xs text-slate-400">#{{ $topup->order_number }} · {{ $topup->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($topup->status === 'paid')
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">{{ __('messages.status_completed') }}</span>
                            @else
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-semibold text-amber-700">{{ __('messages.status_pending') }}</span>
                                <a href="{{ route('wallet.topup.show', $topup->id) }}" class="text-orange-500 hover:text-orange-600" title="{{ __('messages.pay_now_qr') }}">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm text-slate-400">{{ __('messages.no_deposit_history') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-medium text-slate-500">{{ __('messages.order_number') }}</th>
                            <th class="px-6 py-3.5 text-xs font-medium text-slate-500">{{ __('messages.order_total') }}</th>
                            <th class="px-6 py-3.5 text-xs font-medium text-slate-500">{{ __('messages.order_status') }}</th>
                            <th class="px-6 py-3.5 text-xs font-medium text-slate-500">{{ __('messages.order_date') }}</th>
                            <th class="px-6 py-3.5"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topupOrders as $topup)
                            <tr class="transition-colors hover:bg-slate-50/50">
                                <td class="px-6 py-4 font-medium text-slate-900">#{{ $topup->order_number }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-900">{{ number_format($topup->total_amount, 0, ',', '.') }} đ</td>
                                <td class="px-6 py-4">
                                    @if($topup->status === 'paid')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ __('messages.status_completed') }}</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700">{{ __('messages.status_pending') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500">{{ $topup->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-right">
                                    @if($topup->status !== 'paid')
                                        <a href="{{ route('wallet.topup.show', $topup->id) }}"
                                           class="text-sm font-medium text-orange-500 hover:text-orange-600">
                                            {{ __('messages.pay_now_qr') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-sm text-slate-400">
                                    {{ __('messages.no_deposit_history') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($topupOrders->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $topupOrders->links() }}
                </div>
            @endif
        </div>

        {{-- Transaction History --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-sm font-bold text-slate-900">{{ __('messages.transaction_history_title') }}</h3>
            </div>

            {{-- Mobile Cards --}}
            <div class="divide-y divide-slate-100 md:hidden">
                @forelse($transactions as $tx)
                    <div class="px-5 py-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-900">{{ $tx->description ?? '-' }}</span>
                            <span class="text-sm font-semibold {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
                            </span>
                        </div>
                        <div class="mt-1 flex items-center justify-between">
                            <span class="text-xs text-slate-400">{{ $tx->created_at->format('d/m/Y H:i') }}</span>
                            <span class="text-xs text-slate-400">{{ __('messages.tx_balance_after') }}: {{ number_format($tx->balance_after, 0, ',', '.') }} đ</span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm text-slate-400">{{ __('messages.no_transactions') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-100 bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-medium text-slate-500">{{ __('messages.tx_date') }}</th>
                            <th class="px-6 py-3.5 text-xs font-medium text-slate-500">{{ __('messages.tx_description') }}</th>
                            <th class="px-6 py-3.5 text-right text-xs font-medium text-slate-500">{{ __('messages.tx_amount') }}</th>
                            <th class="px-6 py-3.5 text-right text-xs font-medium text-slate-500">{{ __('messages.tx_balance_after') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transactions as $tx)
                            <tr class="transition-colors hover:bg-slate-50/50">
                                <td class="px-6 py-4 text-slate-500">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $tx->description ?? '-' }}</td>
                                <td class="px-6 py-4 text-right font-semibold {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-slate-900">
                                    {{ number_format($tx->balance_after, 0, ',', '.') }} đ
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-sm text-slate-400">
                                    {{ __('messages.no_transactions') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function selectPreset(amount, button) {
            document.querySelectorAll('.preset-btn').forEach(btn => {
                btn.classList.remove('border-orange-500', 'bg-orange-50', 'text-orange-600');
                btn.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
            });

            button.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
            button.classList.add('border-orange-500', 'bg-orange-50', 'text-orange-600');

            document.getElementById('topup-amount').value = amount;
        }
    </script>
@endpush
