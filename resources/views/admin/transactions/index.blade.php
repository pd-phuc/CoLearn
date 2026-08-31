@extends('admin.layouts.admin')

@section('admin-content')
    <div class="space-y-6">
        {{-- Summary Stat Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card
                :label="__('admin.total_deposits')"
                :value="number_format($totalIn, 0, ',', '.') . ' đ'"
                color="emerald"
            >
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 11l5-5m0 0l5 5m-5-5v12"
                        />
                    </svg>
                </x-slot>
            </x-stat-card>
            <x-stat-card
                :label="__('admin.total_withdrawals')"
                :value="number_format($totalOut, 0, ',', '.') . ' đ'"
                color="rose"
            >
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 13l-5 5m0 0l-5-5m5 5V6"
                        />
                    </svg>
                </x-slot>
            </x-stat-card>
            <x-stat-card :label="__('admin.total_transactions')" :value="number_format($totalCount)" color="blue">
                <x-slot:icon>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                        />
                    </svg>
                </x-slot>
            </x-stat-card>
        </div>

        {{-- Filter Bar --}}
        <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('admin.filter_type') }}</label>
                <select
                    name="type"
                    class="px-3 py-2 text-sm font-semibold border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-orange-500/30 focus:border-orange-400 outline-none"
                >
                    <option value="">{{ __('admin.all_types') }}</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>
                        {{ __('messages.tx_type_in') }}
                    </option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>
                        {{ __('messages.tx_type_out') }}
                    </option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">{{ __('admin.filter_action') }}</label>
                <select
                    name="action"
                    class="px-3 py-2 text-sm font-semibold border border-slate-200 rounded-xl bg-white focus:ring-2 focus:ring-orange-500/30 focus:border-orange-400 outline-none"
                >
                    <option value="">{{ __('admin.all_actions') }}</option>
                    <option value="buy_course" {{ request('action') === 'buy_course' ? 'selected' : '' }}>
                        {{ __('messages.tx_buy_course') }}
                    </option>
                    <option value="deposit_bank" {{ request('action') === 'deposit_bank' ? 'selected' : '' }}>
                        {{ __('messages.tx_deposit_bank') }}
                    </option>
                    <option value="admin_deposit" {{ request('action') === 'admin_deposit' ? 'selected' : '' }}>
                        {{ __('messages.tx_admin_deposit') }}
                    </option>
                    <option value="admin_withdraw" {{ request('action') === 'admin_withdraw' ? 'selected' : '' }}>
                        {{ __('messages.tx_admin_withdraw') }}
                    </option>
                    <option value="refund" {{ request('action') === 'refund' ? 'selected' : '' }}>
                        {{ __('messages.tx_refund') }}
                    </option>
                </select>
            </div>
            <button
                type="submit"
                class="px-4 py-2 text-sm font-bold text-white bg-orange-500 hover:bg-orange-600 rounded-xl transition-colors shadow-sm"
            >
                {{ __('admin.apply_filter') }}
            </button>
            @if (request('type') || request('action'))
                <a
                    href="{{ route('admin.transactions.index') }}"
                    class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-900 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors"
                >
                    {{ __('admin.clear_filter') }}
                </a>
            @endif
        </form>

        {{-- Count Badge --}}
        <div class="flex items-center justify-between">
            <span
                class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-extrabold text-slate-700 shadow-2xs"
            >
                {{ __('admin.total_logged_entries', ['count' => $transactions->total()]) }}
            </span>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-200/80">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.user_account') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.tx_event') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('messages.tx_amount') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('messages.tx_balance_after') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.date_time') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse ($transactions as $tx)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($tx->user?->avatar)
                                            <img
                                                src="{{ $tx->user->avatar }}"
                                                class="w-8 h-8 rounded-full object-cover ring-2 ring-orange-500/20"
                                            />
                                        @else
                                            <div
                                                class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-black text-xs"
                                            >
                                                {{ strtoupper(substr($tx->user?->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-900">
                                                {{ $tx->user?->name ?? 'N/A' }}
                                            </p>
                                            <p class="text-xs text-slate-400 font-medium">{{ $tx->user?->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">
                                    {{ $tx->action_label }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm font-black {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}"
                                >
                                    {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                                    đ
                                </td>
                                <td class="px-6 py-4 text-xs font-extrabold text-slate-900">
                                    {{ number_format($tx->balance_after, 0, ',', '.') }} đ
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500">
                                    {{ $tx->created_at->format('d/m/Y H:i:s') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-xs font-bold text-slate-400">
                                    {{ __('messages.no_transactions') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $transactions->links() }}</div>
    </div>
@endsection
