<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        protected CartService $cartService,
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

        // Check response code: '00' is success in VNPay, or stripe_success == 1
        $isSuccess = ($responseCode === '00') || $request->has('stripe_success') || $request->has('mock_stripe');

        if ($isSuccess) {
            if ($order->status !== 'paid') {
                $order->update([
                    'status' => 'paid',
                    'payment_id' => $transactionNo,
                    'paid_at' => now(),
                ]);

                // Create Auto-Enrollment for all ordered courses
                foreach ($order->items as $item) {
                    Enrollment::firstOrCreate([
                        'user_id' => $order->user_id,
                        'course_id' => $item->course_id,
                    ], [
                        'status' => 'active',
                        'enrolled_at' => now(),
                    ]);
                }

                // Clear cart after successful purchase
                $this->cartService->clear();
            }

            return redirect()->route('orders.show', $order->id)
                ->with('success', __('messages.payment_success_enrolled'));
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('cart.index')
            ->with('error', __('messages.payment_failed_try_again'));
    }
}
