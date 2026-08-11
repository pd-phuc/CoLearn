@extends('admin.layouts.admin')

@section('admin-content')
<div class="space-y-6">
    {{-- Info Card --}}
    <div class="flex items-center justify-between">
        <span class="px-3.5 py-1.5 bg-white border border-slate-200/80 rounded-xl text-xs font-extrabold text-slate-700 shadow-2xs">
            {{ __('admin.total_logged_entries', ['count' => $transactions->total()]) }}
        </span>
    </div>

    {{-- Transactions Table --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 border-b border-slate-200/80">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('admin.user_account') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('admin.tx_event') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.tx_amount') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.tx_balance_after') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('admin.date_time') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($tx->user?->avatar)
                                        <img src="{{ $tx->user->avatar }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-orange-500/20">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 font-black text-xs">
                                            {{ strtoupper(substr($tx->user?->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-extrabold text-slate-900">{{ $tx->user?->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-slate-400 font-medium">{{ $tx->user?->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-700">
                                {{ $tx->action_label }}
                            </td>
                            <td class="px-6 py-4 text-sm font-black {{ $tx->type === 'in' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $tx->type === 'in' ? '+' : '-' }}{{ number_format($tx->amount, 0, ',', '.') }} đ
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
