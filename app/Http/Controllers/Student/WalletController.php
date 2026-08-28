<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SePayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function __construct(protected SePayService $sePayService) {}

    public function index(): View
    {
        $user = auth()->user();
        $topupOrders = $user->orders()
            ->where('order_type', 'topup')
            ->latest()
            ->paginate(10, ['*'], 'topup_page');

        $transactions = $user->transactions()
            ->latest()
            ->paginate(10, ['*'], 'tx_page');

        return view('wallet.index', compact('user', 'topupOrders', 'transactions'));
    }

    public function topup(Request $request): View|RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:10000', 'max:50000000'],
        ]);

        $user = auth()->user();
        $amount = (float) $request->input('amount');
        $orderNumber = 'ORD-TOPUP-'.date('Ymd').'-'.strtoupper(Str::random(5));

        $order = Order::create([
            'order_number' => $orderNumber,
            'order_type' => 'topup',
            'user_id' => $user->id,
            'subtotal' => $amount,
            'discount_amount' => 0.00,
            'total_amount' => $amount,
            'status' => 'pending',
            'payment_method' => 'sepay',
            'expires_at' => now()->addMinutes(15),
        ]);

        $vietQrData = $this->sePayService->generateVietQrData($order);
        $topupOrders = $user->orders()
            ->where('order_type', 'topup')
            ->latest()
            ->paginate(10, ['*'], 'topup_page');

        $transactions = $user->transactions()
            ->latest()
            ->paginate(10, ['*'], 'tx_page');

        return view('wallet.index', [
            'user' => $user,
            'topupOrders' => $topupOrders,
            'transactions' => $transactions,
            'order' => $order,
            'vietQrModal' => true,
            'vietQrData' => $vietQrData,
        ]);
    }

    public function showPendingTopup(Order $order): View|RedirectResponse
    {
        Gate::authorize('view', $order);

        if ($order->status === 'paid') {
            return redirect()->route('wallet.index');
        }

        if ($order->isExpired()) {
            return redirect()->route('wallet.index')
                ->with('error', __('messages.order_expired'));
        }

        $user = auth()->user();
        $vietQrData = $this->sePayService->generateVietQrData($order);
        $topupOrders = $user->orders()
            ->where('order_type', 'topup')
            ->latest()
            ->paginate(10, ['*'], 'topup_page');

        $transactions = $user->transactions()
            ->latest()
            ->paginate(10, ['*'], 'tx_page');

        return view('wallet.index', [
            'user' => $user,
            'topupOrders' => $topupOrders,
            'transactions' => $transactions,
            'order' => $order,
            'vietQrModal' => true,
            'vietQrData' => $vietQrData,
        ]);
    }
}
