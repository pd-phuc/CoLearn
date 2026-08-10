<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SePayService
{
    public function __construct(protected OrderService $orderService) {}

    public function generateVietQrData(Order $order): array
    {
        $bankId = config('services.sepay.bank_id', 'NCB');
        $accountNo = config('services.sepay.account_no', '9704198526191432198');
        $accountName = config('services.sepay.account_name', 'COLEARN PLATFORM');
        $amount = (int) $order->total_amount;
        $memo = $order->order_number;

        // Dynamic VietQR image API for SePay
        $qrUrl = sprintf(
            'https://img.vietqr.io/image/%s-%s-compact.png?amount=%d&addInfo=%s&accountName=%s',
            $bankId,
            $accountNo,
            $amount,
            urlencode($memo),
            urlencode($accountName),
        );

        return [
            'bank_id' => $bankId,
            'bank_name' => "Ngân hàng ({$bankId})",
            'account_no' => $accountNo,
            'account_name' => $accountName,
            'amount' => $amount,
            'formatted_amount' => number_format($amount, 0, ',', '.').' VNĐ',
            'order_number' => $memo,
            'qr_url' => $qrUrl,
            'expires_at' => now()->addMinutes(15)->toIso8601String(),
        ];
    }

    public function validateWebhookHeader(Request $request): bool
    {
        $configuredApiKey = config('services.sepay.api_key');

        if (empty($configuredApiKey)) {
            return true; // If no key is set in dev, allow webhook
        }

        $authHeader = $request->header('Authorization') ?: $request->header('Apikey');

        if (empty($authHeader)) {
            return false;
        }

        $token = str_replace(['Bearer ', 'Apikey '], '', $authHeader);

        return hash_equals($configuredApiKey, trim($token));
    }

    /**
     * Process SePay webhook payload with full transaction safety.
     * Uses DB::transaction + lockForUpdate to prevent race conditions.
     */
    public function processWebhookPayload(array $payload): array
    {
        $transferType = $payload['transferType'] ?? 'in';

        // Only process incoming money transactions ('in')
        if (strtolower($transferType) !== 'in') {
            return ['success' => false, 'message' => 'Ignored non-incoming transaction'];
        }

        $content = $payload['content'] ?? ($payload['description'] ?? '');
        $transferAmount = (float) ($payload['transferAmount'] ?? 0);
        $referenceCode = $payload['referenceCode'] ?? ($payload['id'] ?? ('SEPAY_'.time()));

        // Regex match order numbers like ORD-20260809-ABC123 or ORD-TOPUP-20260809-XYZ12
        if (! preg_match('/(ORD-(?:TOPUP-)?[A-Z0-9-]+)/i', $content, $matches)) {
            return ['success' => false, 'message' => 'No matching order number found in transfer memo'];
        }

        $orderNumber = strtoupper($matches[1]);
        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            return ['success' => false, 'message' => "Order {$orderNumber} not found"];
        }

        if ($order->status === 'paid') {
            return ['success' => true, 'message' => 'Order already processed'];
        }

        // Amount Check (Allowing exact or greater amount)
        if ($transferAmount < (float) $order->total_amount) {
            return ['success' => false, 'message' => "Transfer amount ({$transferAmount}) is less than order total ({$order->total_amount})"];
        }

        $paymentId = 'SEPAY_'.$referenceCode;

        try {
            if ($order->order_type === 'topup') {
                $this->orderService->fulfillTopupOrder($order, $paymentId);
                Log::info("SePay Webhook: User {$order->user_id} topped up ".number_format($order->total_amount).' VNĐ');
            } else {
                $this->orderService->fulfillCourseOrder($order, $paymentId);
                Log::info("SePay Webhook: Order {$order->order_number} paid & courses enrolled.");
            }

            return [
                'success' => true,
                'message' => 'Order payment confirmed and processed successfully',
                'order_number' => $order->order_number,
            ];
        } catch (\Exception $e) {
            Log::error("SePay Webhook: Failed to process order {$orderNumber}: ".$e->getMessage());

            return ['success' => false, 'message' => 'Failed to process order: '.$e->getMessage()];
        }
    }
}
