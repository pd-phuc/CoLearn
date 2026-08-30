<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coupon extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_discount_amount',
        'min_order_amount',
        'max_uses',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'discount_value' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function isValidFor(float $subtotal): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return false;
        }

        if ($this->starts_at !== null && now()->lt($this->starts_at)) {
            return false;
        }

        if ($this->expires_at !== null && now()->gt($this->expires_at)) {
            return false;
        }

        if ($this->min_order_amount !== null && $subtotal < (float) $this->min_order_amount) {
            return false;
        }

        return true;
    }

    /**
     * Computed coupon status for UI display.
     *
     * @return string One of: active, scheduled, expired, exhausted, disabled
     */
    public function getStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'disabled';
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return 'exhausted';
        }

        if ($this->starts_at !== null && now()->lt($this->starts_at)) {
            return 'scheduled';
        }

        if ($this->expires_at !== null && now()->gt($this->expires_at)) {
            return 'expired';
        }

        return 'active';
    }

    public function calculateDiscount(float $subtotal): float
    {
        if (! $this->isValidFor($subtotal)) {
            return 0.00;
        }

        if ($this->discount_type === 'percent') {
            $discount = round($subtotal * ((float) $this->discount_value / 100), 2);

            if ($this->max_discount_amount !== null) {
                $discount = min($discount, (float) $this->max_discount_amount);
            }

            return min($discount, $subtotal);
        }

        return min($subtotal, (float) $this->discount_value);
    }
}
