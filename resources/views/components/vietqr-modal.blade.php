{{-- Backward compatibility wrapper for sepay-modal --}}

@props([
    'order',
    'vietQrData' => null,
    'paymentData' => null,
    'redirectUrl' => null,
])

<x-sepay-modal :order="$order" :payment-data="$paymentData ?? $vietQrData" :redirect-url="$redirectUrl" />
