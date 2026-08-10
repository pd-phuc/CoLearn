<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'type',
        'action',
        'balance_before',
        'balance_after',
        'description',
        'reference_id',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'buy_course' => __('messages.tx_buy_course'),
            'deposit_bank' => __('messages.tx_deposit_bank'),
            'admin_deposit' => __('messages.tx_admin_deposit'),
            'admin_withdraw' => __('messages.tx_admin_withdraw'),
            'refund' => __('messages.tx_refund'),
            default => str_replace('_', ' ', (string) $this->action),
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'in'
            ? __('messages.tx_type_in')
            : __('messages.tx_type_out');
    }
}
