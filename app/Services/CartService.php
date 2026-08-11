<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Course;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const CART_SESSION_KEY = 'colearn_cart_items';

    protected const COUPON_SESSION_KEY = 'colearn_cart_coupon';

    public function getCourseIds(): array
    {
        return Session::get(self::CART_SESSION_KEY, []);
    }

    public function add(Course $course): bool
    {
        $ids = $this->getCourseIds();

        if (! in_array($course->id, $ids, true)) {
            $ids[] = $course->id;
            Session::put(self::CART_SESSION_KEY, $ids);

            return true;
        }

        return false;
    }

    public function remove(string $courseId): void
    {
        $ids = array_filter($this->getCourseIds(), fn ($id) => $id !== $courseId);
        Session::put(self::CART_SESSION_KEY, array_values($ids));

        // Re-validate coupon if cart total changed below minimum requirement
        if ($this->getCoupon() && ! $this->getCoupon()->isValidFor($this->getSubtotal())) {
            $this->removeCoupon();
        }
    }

    public function clear(): void
    {
        Session::forget(self::CART_SESSION_KEY);
        Session::forget(self::COUPON_SESSION_KEY);
    }

    public function getItems(): Collection
    {
        $ids = $this->getCourseIds();

        if (empty($ids)) {
            return collect();
        }

        return Course::with(['category', 'teacher'])->whereIn('id', $ids)->get();
    }

    public function count(): int
    {
        return count($this->getCourseIds());
    }

    public function getSubtotal(): float
    {
        return (float) $this->getItems()->sum('price');
    }

    public function applyCoupon(string $code): array
    {
        $code = strtoupper(trim($code));
        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return ['success' => false, 'message' => __('messages.coupon_invalid')];
        }

        $subtotal = $this->getSubtotal();

        if (! $coupon->isValidFor($subtotal)) {
            return ['success' => false, 'message' => __('messages.coupon_expired_or_invalid')];
        }

        Session::put(self::COUPON_SESSION_KEY, $coupon->code);

        return [
            'success' => true,
            'coupon' => $coupon,
            'message' => __('messages.coupon_applied_success'),
        ];
    }

    public function removeCoupon(): void
    {
        Session::forget(self::COUPON_SESSION_KEY);
    }

    public function getCoupon(): ?Coupon
    {
        $code = Session::get(self::COUPON_SESSION_KEY);

        if (! $code) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon || ! $coupon->isValidFor($this->getSubtotal())) {
            $this->removeCoupon();

            return null;
        }

        return $coupon;
    }

    public function getDiscount(): float
    {
        $coupon = $this->getCoupon();

        if (! $coupon) {
            return 0.00;
        }

        return $coupon->calculateDiscount($this->getSubtotal());
    }

    public function getTotal(): float
    {
        return max(0.00, $this->getSubtotal() - $this->getDiscount());
    }
}
