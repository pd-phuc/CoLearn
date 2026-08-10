<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class PaymentIpnController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
    ) {}

    public function status(Order $order): JsonResponse
    {
        return response()->json([
            'status' => $order->status,
            'paid' => $order->status === 'paid',
            'redirect' => route('orders.show', $order->id),
        ]);
    }

    /**
     * Simulated payment for sandbox/development mode only.
     */
    public function simulatedPay(Order $order): JsonResponse
    {
        // Security: only allow in local/testing environment
        if (! app()->environment('local', 'testing')) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        // Security: verify order belongs to authenticated user
        if ($order->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($order->status !== 'paid') {
            $paymentId = 'SIMULATED_'.rand(10000, 99999);

            if ($order->order_type === 'topup') {
                $this->orderService->fulfillTopupOrder($order, $paymentId);
            } else {
                $this->orderService->fulfillCourseOrder($order, $paymentId, $this->cartService);
            }
        }

        return response()->json([
            'success' => true,
            'paid' => true,
            'redirect' => route('orders.show', $order->id),
        ]);
    }
}
