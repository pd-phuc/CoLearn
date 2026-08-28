<?php

namespace Tests\Unit;

use App\Models\Coupon;
use Tests\TestCase;

class CouponDiscountTest extends TestCase
{
    private function makeCoupon(array $overrides = []): Coupon
    {
        return new Coupon(array_merge([
            'code' => 'TEST',
            'discount_type' => 'percent',
            'discount_value' => 50,
            'max_discount_amount' => null,
            'min_order_amount' => null,
            'max_uses' => null,
            'used_count' => 0,
            'is_active' => true,
            'starts_at' => null,
            'expires_at' => null,
        ], $overrides));
    }

    public function test_percentage_coupon_without_cap(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percent',
            'discount_value' => 20,
        ]);

        $this->assertEquals(100000.00, $coupon->calculateDiscount(500000));
    }

    public function test_percentage_coupon_with_cap(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percent',
            'discount_value' => 90,
            'max_discount_amount' => 500000,
        ]);

        // 90% of 50,000,000 = 45,000,000 but capped at 500,000
        $this->assertEquals(500000.00, $coupon->calculateDiscount(50000000));
    }

    public function test_percentage_coupon_cap_not_reached(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percent',
            'discount_value' => 10,
            'max_discount_amount' => 500000,
        ]);

        // 10% of 100,000 = 10,000 (below cap)
        $this->assertEquals(10000.00, $coupon->calculateDiscount(100000));
    }

    public function test_percentage_coupon_never_exceeds_subtotal(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percent',
            'discount_value' => 100,
            'max_discount_amount' => null,
        ]);

        $this->assertEquals(200000.00, $coupon->calculateDiscount(200000));
    }

    public function test_fixed_coupon_capped_at_subtotal(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'fixed',
            'discount_value' => 500000,
        ]);

        // Fixed 500,000 on a 100,000 order -> capped at subtotal
        $this->assertEquals(100000.00, $coupon->calculateDiscount(100000));
    }

    public function test_zero_subtotal_returns_zero(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percent',
            'discount_value' => 50,
            'max_discount_amount' => 100000,
        ]);

        $this->assertEquals(0.00, $coupon->calculateDiscount(0));
    }

    public function test_inactive_coupon_returns_zero(): void
    {
        $coupon = $this->makeCoupon([
            'is_active' => false,
            'discount_type' => 'percent',
            'discount_value' => 50,
        ]);

        $this->assertEquals(0.00, $coupon->calculateDiscount(500000));
    }

    public function test_very_large_subtotal_is_capped(): void
    {
        $coupon = $this->makeCoupon([
            'discount_type' => 'percent',
            'discount_value' => 50,
            'max_discount_amount' => 1000000,
        ]);

        // 50% of 100,000,000 = 50,000,000 but capped at 1,000,000
        $this->assertEquals(1000000.00, $coupon->calculateDiscount(100000000));
    }
}
