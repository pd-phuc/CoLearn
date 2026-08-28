<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PaymentIpnController extends Controller
{
    public function status(Order $order): JsonResponse
    {
        Gate::authorize('view', $order);

        return response()->json([
            'status' => $order->status,
            'paid' => $order->status === 'paid',
            'redirect' => route('orders.show', $order->id),
        ]);
    }
}
