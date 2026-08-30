<?php

namespace Tests\Unit;

use App\Models\Coupon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CouponStatusTest extends TestCase
{
    use DatabaseTransactions;

    private function makeCoupon(array $attributes = []): Coupon
    {
        return Coupon::create(array_merge([
            'code' => 'TEST'.strtoupper(uniqid()),
            'discount_type' => 'percent',
            'discount_value' => 10,
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addDay(),
            'max_uses' => null,
            'used_count' => 0,
        ], $attributes));
    }

    public function test_active_coupon_returns_active(): void
    {
        $coupon = $this->makeCoupon();
        $this->assertEquals('active', $coupon->status);
    }

    public function test_disabled_coupon_returns_disabled(): void
    {
        $coupon = $this->makeCoupon(['is_active' => false]);
        $this->assertEquals('disabled', $coupon->status);
    }

    public function test_expired_coupon_returns_expired(): void
    {
        $coupon = $this->makeCoupon([
            'starts_at' => now()->subWeek(),
            'expires_at' => now()->subDay(),
        ]);
        $this->assertEquals('expired', $coupon->status);
    }

    public function test_scheduled_coupon_returns_scheduled(): void
    {
        $coupon = $this->makeCoupon([
            'starts_at' => now()->addDay(),
            'expires_at' => now()->addWeek(),
        ]);
        $this->assertEquals('scheduled', $coupon->status);
    }

    public function test_exhausted_coupon_returns_exhausted(): void
    {
        $coupon = $this->makeCoupon([
            'max_uses' => 5,
            'used_count' => 5,
        ]);
        $this->assertEquals('exhausted', $coupon->status);
    }

    public function test_null_dates_active_coupon_returns_active(): void
    {
        $coupon = $this->makeCoupon([
            'starts_at' => null,
            'expires_at' => null,
            'max_uses' => null,
        ]);
        $this->assertEquals('active', $coupon->status);
    }

    public function test_disabled_takes_precedence_over_expired(): void
    {
        $coupon = $this->makeCoupon([
            'is_active' => false,
            'expires_at' => now()->subDay(),
        ]);
        $this->assertEquals('disabled', $coupon->status);
    }
}
