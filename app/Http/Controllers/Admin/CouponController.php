<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCouponRequest;
use App\Http\Requests\Admin\UpdateCouponRequest;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::withCount('orders')->latest()->paginate(20);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return view('admin.coupons.form', ['coupon' => null]);
    }

    public function store(StoreCouponRequest $request): RedirectResponse
    {
        Coupon::create($request->validated());

        return redirect()->route('admin.coupons.index')->with('success', __('admin.coupon_created'));
    }

    public function edit(Coupon $coupon): View
    {
        return view('admin.coupons.form', compact('coupon'));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon): RedirectResponse
    {
        $coupon->update($request->validated());

        return redirect()->route('admin.coupons.index')->with('success', __('admin.coupon_updated'));
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        if ($coupon->orders()->exists()) {
            return back()->with('error', __('admin.cannot_delete_used_coupon'));
        }

        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', __('admin.coupon_deleted'));
    }
}
