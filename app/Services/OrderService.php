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
    public function __construct(protected WalletService $walletService) {}

    /**
     * Process wallet payment: lock user balance, deduct, and fulfill order.
     * This is the ONLY safe way to pay with wallet balance.
     *
     * @throws \Exception
     */
    public function processWalletPayment(Order $order, CartService $cartService): void
    {
        DB::transaction(function () use ($order, $cartService) {
            // 1. Lock the order to prevent double-processing
            $order = Order::where('id', $order->id)->lockForUpdate()->first();
            if ($order->status === 'paid') {
                return; // Already processed — idempotent
            }

            // 2. Generate wallet payment ID
            $paymentId = 'WALLET_'.strtoupper(Str::random(8));

            // 3. Deduct balance (WalletService handles locking + balance check)
            $user = User::findOrFail($order->user_id);
            $courseNames = $order->items->map(fn ($item) => $item->course->title)->implode(', ');
            $this->walletService->deduct(
                $user,
                (int) $order->total_amount,
                'buy_course',
                __('messages.tx_desc_buy_course', ['courses' => $courseNames]),
                $order->id,
                $paymentId,
            );

            // 4. Mark order as paid
            $order->update([
                'status' => 'paid',
                'payment_id' => $paymentId,
                'paid_at' => now(),
            ]);

            // 5. Increment coupon usage (only after successful payment)
            if ($order->coupon_id) {
                $order->coupon?->increment('used_count');
            }

            // 6. Auto-enroll in all courses
            $this->enrollCoursesFromOrder($order);

            // 7. Clear cart
            $cartService->clear();

            Log::info("OrderService: Wallet payment completed for order {$order->order_number}, user {$user->id}, amount {$order->total_amount}");
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

            $order->update([
                'status' => 'paid',
                'payment_id' => $paymentId,
                'paid_at' => now(),
            ]);

            // Deposit via WalletService (handles locking + transaction log)
            $user = User::findOrFail($order->user_id);
            $this->walletService->deposit(
                $user,
                (int) $order->total_amount,
                'deposit_bank',
                __('messages.tx_desc_topup', ['order' => $order->order_number]),
                $order->id,
                $paymentId,
            );

            Log::info("OrderService: Topup order {$order->order_number} fulfilled, user {$user->id} deposited {$order->total_amount}");
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
