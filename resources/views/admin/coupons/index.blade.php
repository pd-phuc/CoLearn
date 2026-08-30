@extends('admin.layouts.admin')

@section('admin-content')
    <div class="space-y-6">
        {{-- Header Action Bar --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span
                    class="px-3 py-1 bg-white border border-slate-200/80 rounded-xl text-xs font-extrabold text-slate-700 shadow-2xs"
                >
                    {{ __('admin.total_coupons', ['count' => $coupons->total()]) }}
                </span>
            </div>
            <a
                href="{{ route('admin.coupons.create') }}"
                class="btn-primary px-4 py-2.5 rounded-xl text-xs font-extrabold shadow-sm flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('admin.add_new_coupon') }}
            </a>
        </div>

        {{-- Coupons Data Table --}}
        <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/80 border-b border-slate-200/80">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.coupon_code_label') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.discount_value') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.redemptions') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('messages.order_status') }}
                            </th>
                            <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">
                                {{ __('admin.expiration') }}
                            </th>
                            <th
                                class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-right"
                            >
                                {{ __('admin.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse ($coupons as $coupon)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 bg-slate-100 text-slate-900 font-mono text-xs font-extrabold rounded-lg border border-slate-200/80 uppercase"
                                    >
                                        {{ $coupon->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-900">
                                    {{ $coupon->discount_type === 'percent' ? $coupon->discount_value . '%' : number_format($coupon->discount_value, 0, ',', '.') . ' đ' }}
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-600">
                                    {{ $coupon->used_count }} / {{ $coupon->max_uses ?? 'Unlimited' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-emerald-50 text-emerald-700 border border-emerald-200/60',
                                            'scheduled' => 'bg-blue-50 text-blue-700 border border-blue-200/60',
                                            'expired' => 'bg-rose-50 text-rose-600 border border-rose-200/60',
                                            'exhausted' => 'bg-amber-50 text-amber-700 border border-amber-200/60',
                                            'disabled' => 'bg-slate-100 text-slate-500 border border-slate-200/60',
                                        ];
                                    @endphp

                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider {{ $statusColors[$coupon->status] ?? 'bg-slate-100 text-slate-500' }}"
                                    >
                                        {{ __('admin.coupon_status_' . $coupon->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500">
                                    {{ $coupon->expires_at?->format('d/m/Y H:i') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('admin.coupons.edit', $coupon) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-orange-50 hover:text-orange-600 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                                    >
                                        <svg
                                            class="w-3.5 h-3.5 text-slate-400"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            />
                                        </svg>
                                        {{ __('admin.edit') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-xs font-bold text-slate-400">
                                    {{ __('admin.no_courses_matching') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">{{ $coupons->links() }}</div>
    </div>
@endsection
