<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\SePayService;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartService $cartService,
        protected SePayService $sePayService,
        protected StripeService $stripeService,
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($this->cartService->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', __('messages.cart_is_empty'));
        }

        $items = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();
        $coupon = $this->cartService->getCoupon();
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal();

        return view('checkout.index', compact('items', 'subtotal', 'coupon', 'discount', 'total'));
    }

    public function process(Request $request): View|RedirectResponse
    {
        $request->validate([
            'payment_method' => ['required', 'string', 'in:wallet,vnpay,stripe'],
        ]);

        if ($this->cartService->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', __('messages.cart_is_empty'));
        }

        $user = auth()->user();
        $items = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();
        $coupon = $this->cartService->getCoupon();
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal();
        $paymentMethod = $request->input('payment_method');

        // Check if paying via CoLearn Wallet
        if ($paymentMethod === 'wallet') {
            if (! $user->hasEnoughBalance($total)) {
                return redirect()->route('checkout.index')
                    ->with('error', __('messages.insufficient_wallet_balance'));
            }
        }

        $orderNumber = 'ORD-'.date('Ymd').'-'.strtoupper(Str::random(6));

        $order = Order::create([
            'order_number' => $orderNumber,
            'order_type' => 'course',
            'user_id' => $user->id,
            'coupon_id' => $coupon?->id,
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total,
            'status' => 'pending',
            'payment_method' => $paymentMethod,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'course_id' => $item->id,
                'price' => $item->price,
            ]);
        }

        if ($coupon) {
            $coupon->increment('used_count');
        }

        // Instant Wallet Payment Execution
        if ($paymentMethod === 'wallet') {
            $user->deduct($total);

            $order->update([
                'status' => 'paid',
                'payment_id' => 'WALLET_'.strtoupper(Str::random(8)),
                'paid_at' => now(),
            ]);

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

            return redirect()->route('orders.show', $order->id)
                ->with('success', __('messages.payment_success_enrolled'));
        }

        if ($paymentMethod === 'vnpay') {
            $vietQrData = $this->sePayService->generateVietQrData($order);

            return view('checkout.index', [
                'items' => $items,
                'subtotal' => $subtotal,
                'coupon' => $coupon,
                'discount' => $discount,
                'total' => $total,
                'order' => $order,
                'vietQrModal' => true,
                'vietQrData' => $vietQrData,
            ]);
        }

        $paymentUrl = $this->stripeService->createPaymentUrl($order);

        return redirect()->away($paymentUrl);
    }
}
