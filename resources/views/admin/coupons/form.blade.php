@extends('admin.layouts.admin')
@section('page-title', $coupon ? 'Edit Coupon: ' . $coupon->code : 'Create Discount Coupon')
@section('page-description', 'Manage promotional discount codes, validity periods, and usage limits')

@section('admin-content')
    <div class="space-y-6">
        <a
            href="{{ route('admin.coupons.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-orange-600 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Coupons
        </a>

        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 sm:p-8 shadow-xs">
            <form
                action="{{ $coupon ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
                method="POST"
                class="space-y-6"
            >
                @csrf
                @if ($coupon)
                    @method('PUT')
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            Coupon Code
                        </label>
                        <input
                            type="text"
                            name="code"
                            value="{{ old('code', $coupon?->code) }}"
                            required
                            placeholder="COLEARN2026"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-mono uppercase font-bold"
                        />
                        @error('code')
                            <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            Discount Type
                        </label>
                        <select
                            name="discount_type"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium"
                        >
                            <option
                                value="percent"
                                {{ old('discount_type', $coupon?->discount_type) === 'percent' ? 'selected' : '' }}
                            >
                                Percentage Discount (%)
                            </option>
                            <option
                                value="fixed"
                                {{ old('discount_type', $coupon?->discount_type) === 'fixed' ? 'selected' : '' }}
                            >
                                Fixed Amount (VND ₫)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            Discount Value
                        </label>
                        <input
                            type="number"
                            name="discount_value"
                            value="{{ old('discount_value', $coupon?->discount_value) }}"
                            required
                            min="0"
                            step="0.01"
                            placeholder="20"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            Max Discount Amount (VND)
                        </label>
                        <input
                            type="number"
                            name="max_discount_amount"
                            value="{{ old('max_discount_amount', $coupon?->max_discount_amount) }}"
                            min="0"
                            step="0.01"
                            placeholder="Leave blank for no cap"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium"
                        />
                        <p class="text-xs text-slate-400 mt-1">Only applies to percentage coupons</p>
                        @error('max_discount_amount')
                            <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            Minimum Order Amount (VND)
                        </label>
                        <input
                            type="number"
                            name="min_order_amount"
                            value="{{ old('min_order_amount', $coupon?->min_order_amount) }}"
                            min="0"
                            placeholder="0"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            Maximum Redemptions Limit
                        </label>
                        <input
                            type="number"
                            name="max_uses"
                            value="{{ old('max_uses', $coupon?->max_uses) }}"
                            min="1"
                            placeholder="Leave blank for unlimited"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium"
                        />
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0" />
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $coupon?->is_active ?? true) ? 'checked' : '' }}
                                class="w-5 h-5 rounded-lg border-slate-300 text-orange-600 focus:ring-orange-500"
                            />
                            <span class="text-sm font-extrabold text-slate-900">Enable Coupon (Active)</span>
                        </label>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            Start Date & Time
                        </label>
                        <input
                            type="datetime-local"
                            name="starts_at"
                            value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d\TH:i')) }}"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium"
                        />
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">
                            Expiration Date & Time
                        </label>
                        <input
                            type="datetime-local"
                            name="expires_at"
                            value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d\TH:i')) }}"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium"
                        />
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 flex justify-end gap-3">
                    <a
                        href="{{ route('admin.coupons.index') }}"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm rounded-xl transition-colors"
                    >
                        Cancel
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl transition-colors shadow-sm shadow-orange-500/20"
                    >
                        {{ $coupon ? 'Update Coupon' : 'Create Coupon' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
