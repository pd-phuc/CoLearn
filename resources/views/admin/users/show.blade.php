@extends('admin.layouts.admin')
@section('page-title', __('admin.user_details_title', ['name' => $user->name]))
@section('page-description', __('admin.user_details_desc'))

@section('admin-content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <a
                href="{{ route('admin.users.index') }}"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-orange-600 transition-colors"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    />
                </svg>
                {{ __('admin.back_to_users') }}
            </a>
            <div class="flex items-center gap-2">
                <a
                    href="{{ route('admin.users.edit', $user) }}"
                    class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-xs font-bold transition-colors"
                >
                    {{ __('admin.edit_profile') }}
                </a>
                @if (! $user->isAdmin())
                    <form action="{{ route('admin.users.toggle-ban', $user) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="px-4 py-2 {{ $user->banned_at ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-rose-100 text-rose-700 hover:bg-rose-200' }} rounded-xl text-xs font-bold transition-colors cursor-pointer"
                        >
                            {{ $user->banned_at ? __('admin.unban_user') : __('admin.ban_account') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            {{-- Left Column: User Profile & Balance Adjustment --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Profile Card --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs text-center space-y-4">
                    @if ($user->avatar)
                        <img
                            src="{{ $user->avatar }}"
                            class="w-20 h-20 rounded-full object-cover ring-4 ring-orange-500/20 mx-auto shadow-md"
                        />
                    @else
                        <div
                            class="w-20 h-20 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black text-2xl flex items-center justify-center mx-auto shadow-md shadow-orange-500/20"
                        >
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900">{{ $user->name }}</h2>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $user->email }}</p>
                    </div>

                    <div class="flex items-center justify-center gap-2">
                        @foreach ($user->roles as $role)
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-100 text-purple-700',
                                    'teacher' => 'bg-blue-100 text-blue-700',
                                    'student' => 'bg-orange-100 text-orange-700',
                                ];
                            @endphp

                            <span
                                class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider {{ $roleColors[$role->name] ?? 'bg-slate-100 text-slate-600' }}"
                            >
                                {{ $role->name }}
                            </span>
                        @endforeach

                        @if ($user->banned_at)
                            <span
                                class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-700"
                            >
                                Banned
                            </span>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-extrabold text-slate-400 uppercase tracking-wider">
                            {{ __('admin.wallet_balance') }}
                        </p>
                        <p class="text-2xl font-black text-emerald-600 mt-1">
                            {{ number_format($user->balance, 0, ',', '.') }} đ
                        </p>
                    </div>
                </div>

                {{-- Balance Adjustment --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                        {{ __('admin.adjust_balance_title') }}
                    </h3>
                    <form action="{{ route('admin.users.adjust-balance', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                {{ __('admin.action_type') }}
                            </label>
                            <select
                                name="type"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none"
                            >
                                <option value="deposit">{{ __('admin.deposit_add') }}</option>
                                <option value="withdraw">{{ __('admin.withdraw_deduct') }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                {{ __('admin.amount_vnd') }}
                            </label>
                            <input
                                type="number"
                                name="amount"
                                required
                                min="1"
                                placeholder="e.g. 100000"
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                {{ __('admin.reason_ref') }}
                            </label>
                            <input
                                type="text"
                                name="reason"
                                required
                                placeholder="Admin manual adjustment..."
                                class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200/80 rounded-xl text-xs font-bold focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none"
                            />
                        </div>

                        <button
                            type="submit"
                            class="w-full py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-xs font-extrabold uppercase shadow-sm cursor-pointer transition-colors"
                        >
                            {{ __('admin.confirm_adjustment') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Right Column: Orders & Transactions History --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Order History --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                            {{ __('admin.order_history') }}
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                            {{ $user->orders->count() }} Orders
                        </span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($user->orders as $order)
                            <div
                                class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors"
                            >
                                <div>
                                    <p class="text-xs font-extrabold text-slate-900">{{ $order->order_number }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-black text-slate-900">
                                        {{ number_format($order->total_amount, 0, ',', '.') }} đ
                                    </p>
                                    @php
                                        $sc = [
                                            'paid' => 'bg-emerald-100 text-emerald-700',
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'cancelled' => 'bg-slate-100 text-slate-500',
                                        ];
                                    @endphp

                                    <span
                                        class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase {{ $sc[$order->status] ?? 'bg-slate-100 text-slate-500' }}"
                                    >
                                        {{ $order->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-xs text-slate-400 font-bold">
                                {{ __('admin.no_order_history') }}
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Transactions History --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                            {{ __('admin.financial_tx_history') }}
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                            {{ $user->transactions->count() }} Entries
                        </span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($user->transactions as $tx)
                            <div
                                class="px-6 py-3.5 flex items-center justify-between hover:bg-slate-50/50 transition-colors"
                            >
                                <div>
                                    <p class="text-xs font-extrabold text-slate-900">{{ $tx->action_label }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">
                                        {{ $tx->created_at->format('d/m/Y H:i') }}
                                    </p>
                                </div>
                                <span
                                    class="text-xs font-black {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}"
                                >
                                    {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }}
                                    đ
                                </span>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-xs text-slate-400 font-bold">
                                {{ __('admin.no_tx_records') }}
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
