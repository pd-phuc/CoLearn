<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected OrderService $orderService,
    ) {}

    public function vnpayReturn(Request $request): RedirectResponse
    {
        $orderNumber = $request->input('vnp_TxnRef') ?: $request->input('order_number');
        $responseCode = $request->input('vnp_ResponseCode');
        $transactionNo = $request->input('vnp_TransactionNo') ?: ('TRX-'.time());

        if (! $orderNumber) {
            return redirect()->route('cart.index')->with('error', __('messages.payment_failed'));
        }

        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            return redirect()->route('cart.index')->with('error', __('messages.order_not_found'));
        }

        // Determine success: VNPay '00' code, or mock/sandbox (local env only)
        $isVnpaySuccess = $responseCode === '00';
        $isSandboxSuccess = app()->environment('local')
            && ($request->has('stripe_success') || $request->has('mock_stripe'));
        $isSuccess = $isVnpaySuccess || $isSandboxSuccess;

        if ($isSuccess) {
            if ($order->status !== 'paid') {
                $this->orderService->fulfillCourseOrder($order, $transactionNo, $this->cartService);
            }

            return redirect()->route('orders.show', $order->id)
                ->with('success', __('messages.payment_success_enrolled'));
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('cart.index')
            ->with('error', __('messages.payment_failed_try_again'));
    }
}
