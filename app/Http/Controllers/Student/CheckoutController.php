<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\OrderService;
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
        protected OrderService $orderService,
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
            'payment_method' => ['required', 'string', 'in:wallet,sepay,stripe'],
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

        // Pre-check: wallet balance (quick fail before creating order)
        if ($paymentMethod === 'wallet') {
            if (! $user->hasEnoughBalance($total)) {
                return redirect()->route('checkout.index')
                    ->with('error', __('messages.insufficient_wallet_balance'));
            }
        }

        // Check if user already enrolled in any of the cart courses
        $enrolledCourseIds = $user->enrollments()->pluck('course_id')->toArray();
        $alreadyEnrolled = $items->filter(fn ($item) => in_array($item->id, $enrolledCourseIds));
        if ($alreadyEnrolled->isNotEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('messages.already_enrolled_in_cart_course', [
                    'course' => $alreadyEnrolled->first()->title,
                ]));
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
            'expires_at' => $paymentMethod === 'sepay' ? now()->addMinutes(15) : null,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'course_id' => $item->id,
                'price' => $item->price,
            ]);
        }

        // Wallet: instant payment via OrderService (DB::transaction + lockForUpdate)
        if ($paymentMethod === 'wallet') {
            try {
                $this->orderService->processWalletPayment($order, $this->cartService);

                return redirect()->route('orders.show', $order->id)
                    ->with('success', __('messages.payment_success_enrolled'));
            } catch (\RuntimeException $e) {
                // Payment failed (insufficient balance under lock, etc.)
                $order->update(['status' => 'cancelled']);

                return redirect()->route('checkout.index')
                    ->with('error', $e->getMessage());
            }
        }

        // VietQR / SePay: create pending order, show QR modal
        // NOTE: coupon is NOT incremented here — only when order is actually paid
        if ($paymentMethod === 'sepay') {
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

        // Stripe: redirect to Stripe Checkout
        $paymentUrl = $this->stripeService->createPaymentUrl($order);

        return redirect()->away($paymentUrl);
    }
}
