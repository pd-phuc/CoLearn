<?php

namespace App\Services;

use App\Models\Order;

class SePayService
{
    public function __construct(
        protected OrderService $orderService,
        protected SettingService $settingService,
    ) {}

    public function isConfigured(): bool
    {
        $bankId = $this->settingService->get('sepay', 'bank_id', config('services.sepay.bank_id'));
        $accountNo = $this->settingService->get('sepay', 'account_no', config('services.sepay.account_no'));

        return ! empty($bankId) && ! empty($accountNo);
    }

    public function generateVietQrData(Order $order): array
    {
        if (! $this->isConfigured()) {
            throw new \RuntimeException('SePay payment gateway is not configured. Please set bank details in Admin → Settings.');
        }

        $bankId = $this->settingService->get('sepay', 'bank_id', config('services.sepay.bank_id'));
        $accountNo = $this->settingService->get('sepay', 'account_no', config('services.sepay.account_no'));
        $accountName = $this->settingService->get('sepay', 'account_name', config('services.sepay.account_name', 'COLEARN'));
        $amount = (int) $order->total_amount;
        $memo = $order->order_number;

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
}
