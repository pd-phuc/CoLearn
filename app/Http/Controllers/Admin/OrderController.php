<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService) {}

    public function index(Request $request): View
    {
        $query = Order::with('user');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where('order_number', 'ilike', "%{$search}%");
        }

        $orders = $query->latest()->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'items.course', 'coupon']);

        return view('admin.orders.show', compact('order'));
    }

    public function refund(Order $order): RedirectResponse
    {
        if ($order->status === 'refunded') {
            return back()->with('error', 'Order already refunded.');
        }

        if ($order->status !== 'paid') {
            return back()->with('error', 'Only paid orders can be refunded.');
        }

        DB::transaction(function () use ($order) {
            $user = User::lockForUpdate()->find($order->user_id);
            $user->deposit((float) $order->total_amount, 'refund', "Refund for order {$order->order_number}", $order->id);

            $order->update(['status' => 'refunded']);
        });

        return back()->with('success', 'Order refunded. Amount credited to user wallet.');
    }
}
