<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    public function createPaymentUrl(Order $order): string
    {
        $stripeSecret = config('services.stripe.secret', env('STRIPE_SECRET'));

        // If Stripe secret is set and package exists, create actual session, else use mock sandbox return
        if ($stripeSecret && class_exists('\Stripe\Stripe')) {
            Stripe::setApiKey($stripeSecret);

            $lineItems = [];
            foreach ($order->items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $item->course->title,
                        ],
                        'unit_amount' => (int) ($item->price * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.vnpay.return', ['order_number' => $order->order_number, 'stripe_success' => 1]),
                'cancel_url' => route('cart.index'),
                'client_reference_id' => $order->order_number,
            ]);

            return $session->url;
        }

        // Mock sandbox fallback URL
        return route('payment.vnpay.return', [
            'vnp_ResponseCode' => '00',
            'vnp_TxnRef' => $order->order_number,
            'vnp_TransactionNo' => 'STRIPE_'.rand(1000, 9999),
            'vnp_Amount' => (int) ($order->total_amount * 100),
            'vnp_BankCode' => 'STRIPE_CREDIT_CARD',
            'vnp_SecureHash' => 'mock_stripe_hash',
            'mock_stripe' => 1,
        ]);
    }
}
