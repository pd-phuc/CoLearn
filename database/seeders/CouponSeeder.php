<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        Coupon::firstOrCreate(
            ['code' => 'COLEARN2026'],
            [
                'discount_type' => 'percent',
                'discount_value' => 20.00,
                'min_order_amount' => 100000.00,
                'max_uses' => 1000,
                'used_count' => 0,
                'is_active' => true,
            ],
        );

        Coupon::firstOrCreate(
            ['code' => 'WELCOME50'],
            [
                'discount_type' => 'fixed',
                'discount_value' => 50000.00,
                'min_order_amount' => 50000.00,
                'max_uses' => 500,
                'used_count' => 0,
                'is_active' => true,
            ],
        );
    }
}
