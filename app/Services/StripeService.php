<?php

namespace App\Services;

use App\Models\Order;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeService
{
    public function __construct(protected SettingService $settingService) {}

    public function isConfigured(): bool
    {
        $secret = $this->settingService->get('stripe', 'secret', config('services.stripe.secret'));

        return ! empty($secret);
    }

    public function createPaymentUrl(Order $order): string
    {
        $stripeSecret = $this->settingService->get('stripe', 'secret', config('services.stripe.secret'));

        if (empty($stripeSecret) || ! class_exists('\Stripe\Stripe')) {
            throw new \RuntimeException('Stripe is not configured. Please set Stripe keys in Admin → Settings.');
        }

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
            'success_url' => route('payment.callback', ['order_number' => $order->order_number, 'vnp_ResponseCode' => '00']),
            'cancel_url' => route('cart.index'),
            'client_reference_id' => $order->order_number,
        ]);

        return $session->url;
    }
}
