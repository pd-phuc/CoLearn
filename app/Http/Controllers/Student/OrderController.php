<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = auth()->user()->orders()
            ->with(['items.course', 'coupon'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['items.course.category', 'coupon', 'user']);

        return view('orders.show', compact('order'));
    }
}
