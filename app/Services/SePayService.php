<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SePayService
{
    public function __construct(
        protected OrderService $orderService,
        protected SettingService $settingService,
    ) {}

    /**
     * Check if SePay gateway credentials are configured.
     */
    public function isConfigured(): bool
    {
        $bankId = $this->settingService->get('sepay', 'bank_id', config('services.sepay.bank_id'));
        $accountNo = $this->settingService->get('sepay', 'account_no', config('services.sepay.account_no'));

        return ! empty($bankId) && ! empty($accountNo);
    }

    /**
     * Generate SePay Dynamic QR Code & Payment Data.
     * Uses SePay's official QR generator endpoint: https://qr.sepay.vn/img
     */
    public function generateSePayQrData(Order $order): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('SePay payment gateway is not configured. Please set bank details in Admin → Settings.');
        }

        $bankId = $this->settingService->get('sepay', 'bank_id', config('services.sepay.bank_id', 'MBBank'));
        $accountNo = $this->settingService->get('sepay', 'account_no', config('services.sepay.account_no', ''));
        $accountName = $this->settingService->get('sepay', 'account_name', config('services.sepay.account_name', 'COLEARN'));
        $amount = (int) $order->total_amount;
        $memo = $order->order_number;

        // SePay Official QR Endpoint — standard compact template
        $template = $this->settingService->get('sepay', 'template', 'compact');
        $qrUrl = sprintf(
            'https://qr.sepay.vn/img?bank=%s&acc=%s&template=%s&amount=%d&des=%s',
            urlencode($bankId),
            urlencode($accountNo),
            urlencode($template),
            $amount,
            urlencode($memo),
        );

        $bankNames = [
            'NCB' => 'Ngân hàng Quốc Dân (NCB)',
            'MB' => 'Ngân hàng Quân Đội (MBBank)',
            'MBBank' => 'Ngân hàng Quân Đội (MBBank)',
            'VCB' => 'Ngân hàng Ngoại Thương (Vietcombank)',
            'Vietcombank' => 'Ngân hàng Ngoại Thương (Vietcombank)',
            'TCB' => 'Ngân hàng Kỹ Thương (Techcombank)',
            'Techcombank' => 'Ngân hàng Kỹ Thương (Techcombank)',
            'ACB' => 'Ngân hàng Á Châu (ACB)',
            'VPB' => 'Ngân hàng VPBank',
            'VPBank' => 'Ngân hàng VPBank',
            'BIDV' => 'Ngân hàng BIDV',
            'CTG' => 'Ngân hàng VietinBank',
            'VietinBank' => 'Ngân hàng VietinBank',
            'TPB' => 'Ngân hàng TPBank',
            'TPBank' => 'Ngân hàng TPBank',
            'VIB' => 'Ngân hàng VIB',
            'STB' => 'Ngân hàng Sacombank',
            'Sacombank' => 'Ngân hàng Sacombank',
        ];
        $bankDisplayName = $bankNames[$bankId] ?? $bankId;

        return [
            'gateway' => 'SePay',
            'bank_id' => $bankId,
            'bank_name' => $bankDisplayName,
            'account_no' => $accountNo,
            'account_name' => $accountName,
            'amount' => $amount,
            'formatted_amount' => number_format($amount, 0, ',', '.').' VNĐ',
            'order_number' => $memo,
            'qr_url' => $qrUrl,
            'expires_at' => now()->addMinutes(15)->toIso8601String(),
        ];
    }

    /**
     * Backward-compatible alias for existing views/controllers.
     */
    public function generateVietQrData(Order $order): array
    {
        return $this->generateSePayQrData($order);
    }

    /**
     * Validate incoming SePay Webhook request authentication.
     * SePay sends header: Authorization: Apikey {API_KEY} (or Bearer {API_KEY})
     */
    public function validateWebhookHeader(Request $request): bool
    {
        $configuredApiKey = $this->settingService->get('sepay', 'api_key', config('services.sepay.api_key'));

        // Skipping authentication is a local convenience only. Anywhere else this
        // must fail closed — an unconfigured key would otherwise let anyone POST a
        // payload and have an order marked paid.
        if (empty($configuredApiKey)) {
            if (! app()->environment('local', 'testing')) {
                Log::critical('SePay Webhook: API key is not configured, rejecting request', [
                    'ip' => $request->ip(),
                    'env' => app()->environment(),
                ]);

                return false;
            }

            Log::warning('SePay Webhook: API key is not configured, accepting unauthenticated request', [
                'env' => app()->environment(),
            ]);

            return true;
        }

        $authHeader = $request->header('Authorization') ?? $request->header('X-SePay-Api-Key');

        if (empty($authHeader)) {
            Log::warning('SePay Webhook: Missing Authorization Header', ['ip' => $request->ip()]);

            return false;
        }

        // Handle "Apikey <TOKEN>" or "Bearer <TOKEN>" or plain token
        $token = preg_replace('/^(Apikey|Bearer)\s+/i', '', trim($authHeader));

        return hash_equals(trim($configuredApiKey), trim($token));
    }

    /**
     * Process incoming SePay Webhook payload to fulfill order / deposit wallet.
     * Payload spec: https://sepay.vn/docs/webhook
     */
    public function processWebhookPayload(array $payload): array
    {
        Log::info('SePay Webhook Received', $payload);

        // 1. Check transfer type (only process money in)
        if (isset($payload['transferType']) && strtolower($payload['transferType']) !== 'in') {
            return [
                'success' => false,
                'message' => 'Ignored non-incoming transaction',
            ];
        }

        // 2. Extract transaction memo/content to find Order Number
        $content = $payload['content'] ?? $payload['description'] ?? '';

        if (empty($content)) {
            Log::warning('SePay Webhook: Empty transaction content', $payload);

            return [
                'success' => false,
                'message' => 'Transaction content is empty',
            ];
        }

        // Match patterns like ORD-TOPUP-20260819-8ZEDX or ORD-20260819-XYZ123
        $orderNumber = null;
        if (preg_match('/ORD(?:-TOPUP)?-\d{8}-[A-Z0-9]+/i', $content, $matches)) {
            $orderNumber = strtoupper($matches[0]);
        }

        if (! $orderNumber) {
            Log::warning("SePay Webhook: Could not parse Order Number from content: {$content}");

            return [
                'success' => false,
                'message' => "Order Number not found in content: {$content}",
            ];
        }

        // 3. Find Order in database
        $order = Order::where('order_number', $orderNumber)->first();

        if (! $order) {
            Log::warning("SePay Webhook: Order not found for number {$orderNumber}");

            return [
                'success' => false,
                'message' => "Order {$orderNumber} not found",
            ];
        }

        // 4. Idempotency check: if already paid, return success
        if ($order->status === 'paid') {
            Log::info("SePay Webhook: Order {$orderNumber} is already paid");

            return [
                'success' => true,
                'message' => 'Order already processed',
                'order_id' => $order->id,
            ];
        }

        // 5. Verify payment amount
        $transferAmount = (float) ($payload['transferAmount'] ?? $payload['amount'] ?? 0);
        if ($transferAmount < (float) $order->total_amount) {
            Log::warning("SePay Webhook: Underpaid order {$orderNumber}. Expected {$order->total_amount}, got {$transferAmount}");

            return [
                'success' => false,
                'message' => "Underpaid: expected {$order->total_amount}, received {$transferAmount}",
                'order_id' => $order->id,
            ];
        }

        // 6. Fulfill order based on order_type
        $paymentId = 'SEPAY_'.($payload['id'] ?? $payload['referenceCode'] ?? strtoupper(Str::random(8)));

        if ($order->order_type === 'topup') {
            $this->orderService->fulfillTopupOrder($order, $paymentId);
        } else {
            $this->orderService->fulfillCourseOrder($order, $paymentId);
        }

        Log::info("SePay Webhook: Successfully processed order {$orderNumber} with payment {$paymentId}");

        return [
            'success' => true,
            'message' => 'Payment processed successfully',
            'order_id' => $order->id,
        ];
    }
}
