<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\ApplyCouponRequest;
use App\Models\Course;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function index(): View
    {
        $items = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();
        $coupon = $this->cartService->getCoupon();
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal();

        return view('cart.index', compact('items', 'subtotal', 'coupon', 'discount', 'total'));
    }

    public function add(Course $course): RedirectResponse
    {
        // Check if student already enrolled in this course
        if (auth()->check() && auth()->user()->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('learn.show', $course->slug)
                ->with('info', __('messages.already_enrolled_notice'));
        }

        $added = $this->cartService->add($course);

        if (! $added) {
            return redirect()->route('cart.index')
                ->with('info', __('messages.course_already_in_cart'));
        }

        return redirect()->route('cart.index')
            ->with('success', __('messages.course_added_to_cart_success'));
    }

    public function remove(string $courseId): RedirectResponse
    {
        $this->cartService->remove($courseId);

        return redirect()->route('cart.index')
            ->with('success', __('messages.course_removed_from_cart_success'));
    }

    public function applyCoupon(ApplyCouponRequest $request): RedirectResponse
    {

        $result = $this->cartService->applyCoupon($request->input('code'));

        if (! $result['success']) {
            return redirect()->route('cart.index')->with('error', $result['message']);
        }

        return redirect()->route('cart.index')->with('success', $result['message']);
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->cartService->removeCoupon();

        return redirect()->route('cart.index')
            ->with('success', __('messages.coupon_removed_success'));
    }
}
