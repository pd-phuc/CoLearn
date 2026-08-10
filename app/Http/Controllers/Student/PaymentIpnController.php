<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;

class PaymentIpnController extends Controller
{
    public function __construct(
        protected CartService $cartService,
    ) {}

    public function status(Order $order): JsonResponse
    {
        return response()->json([
            'status' => $order->status,
            'paid' => $order->status === 'paid',
            'redirect' => route('orders.show', $order->id),
        ]);
    }

    public function simulatedPay(Order $order): JsonResponse
    {
        if ($order->status !== 'paid') {
            $order->update([
                'status' => 'paid',
                'payment_id' => 'VIETQR_SIMULATED_'.rand(10000, 99999),
                'paid_at' => now(),
            ]);

            if ($order->order_type === 'topup') {
                $order->user->deposit((float) $order->total_amount);
            } else {
                foreach ($order->items as $item) {
                    Enrollment::firstOrCreate([
                        'user_id' => $order->user_id,
                        'course_id' => $item->course_id,
                    ], [
                        'status' => 'active',
                        'enrolled_at' => now(),
                    ]);
                }
                $this->cartService->clear();
            }
        }

        return response()->json([
            'success' => true,
            'paid' => true,
            'redirect' => route('orders.show', $order->id),
        ]);
    }
}
