<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Process wallet payment: lock user balance, deduct, and fulfill order.
     * This is the ONLY safe way to pay with wallet balance.
     *
     * @throws \Exception
     */
    public function processWalletPayment(Order $order, CartService $cartService): void
    {
        DB::transaction(function () use ($order, $cartService) {
            // 1. Lock user row to prevent race condition (double-spend)
            $user = User::where('id', $order->user_id)->lockForUpdate()->first();

            if (! $user) {
                throw new \RuntimeException('User not found');
            }

            // 2. Re-verify balance under lock
            $total = (float) $order->total_amount;
            if (! $user->hasEnoughBalance($total)) {
                throw new \RuntimeException(__('messages.insufficient_wallet_balance'));
            }

            // 3. Lock the order to prevent double-processing
            $order = Order::where('id', $order->id)->lockForUpdate()->first();
            if ($order->status === 'paid') {
                return; // Already processed — idempotent
            }

            // 4. Generate wallet payment ID
            $paymentId = 'WALLET_'.strtoupper(Str::random(8));

            // 5. Deduct balance (with Transaction log)
            $courseNames = $order->items->map(fn ($item) => $item->course->title)->implode(', ');
            $user->deduct(
                $total,
                'buy_course',
                __('messages.tx_desc_buy_course', ['courses' => $courseNames]),
                $order->id,
                $paymentId,
            );

            // 6. Mark order as paid
            $order->update([
                'status' => 'paid',
                'payment_id' => $paymentId,
                'paid_at' => now(),
            ]);

            // 7. Increment coupon usage (only after successful payment)
            if ($order->coupon_id) {
                $order->coupon?->increment('used_count');
            }

            // 8. Auto-enroll in all courses
            $this->enrollCoursesFromOrder($order);

            // 9. Clear cart
            $cartService->clear();

            Log::info("OrderService: Wallet payment completed for order {$order->order_number}, user {$user->id}, amount {$total}");
        });
    }

    /**
     * Fulfill a course purchase order (mark paid + enroll).
     * Used by SePay webhook, Stripe return, simulated pay.
     * MUST be called within a DB::transaction or will create its own.
     */
    public function fulfillCourseOrder(Order $order, string $paymentId, ?CartService $cartService = null): void
    {
        DB::transaction(function () use ($order, $paymentId, $cartService) {
            // Lock order row to prevent double-processing
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            if ($order->status === 'paid') {
                return; // Already processed — idempotent
            }

            $order->update([
                'status' => 'paid',
                'payment_id' => $paymentId,
                'paid_at' => now(),
            ]);

            // Increment coupon usage now that payment is confirmed
            if ($order->coupon_id) {
                $order->coupon?->increment('used_count');
            }

            $this->enrollCoursesFromOrder($order);

            // Clear cart if available
            $cartService?->clear();

            Log::info("OrderService: Course order {$order->order_number} fulfilled with payment {$paymentId}");
        });
    }

    /**
     * Fulfill a wallet topup order (mark paid + deposit balance).
     * Used by SePay webhook and simulated pay.
     */
    public function fulfillTopupOrder(Order $order, string $paymentId): void
    {
        DB::transaction(function () use ($order, $paymentId) {
            // Lock order to prevent double-processing
            $order = Order::where('id', $order->id)->lockForUpdate()->first();

            if ($order->status === 'paid') {
                return; // Already processed — idempotent
            }

            // Lock user row before modifying balance
            $user = User::where('id', $order->user_id)->lockForUpdate()->first();

            $amount = (float) $order->total_amount;

            $order->update([
                'status' => 'paid',
                'payment_id' => $paymentId,
                'paid_at' => now(),
            ]);

            // Deposit with Transaction log
            $user->deposit(
                $amount,
                'deposit_bank',
                __('messages.tx_desc_topup', ['order' => $order->order_number]),
                $order->id,
                $paymentId,
            );

            Log::info("OrderService: Topup order {$order->order_number} fulfilled, user {$user->id} deposited {$amount}");
        });
    }

    /**
     * Enroll user in all courses from an order.
     */
    private function enrollCoursesFromOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            Enrollment::firstOrCreate([
                'user_id' => $order->user_id,
                'course_id' => $item->course_id,
            ], [
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }
    }
}
