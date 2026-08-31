<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdjustBalanceRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(protected WalletService $walletService) {}

    public function index(Request $request): View
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->role($role);
        }

        $users = $query->latest()->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $user->load(['orders' => fn ($q) => $q->latest()->limit(10), 'enrollments.course', 'transactions' => fn ($q) => $q->latest()->limit(10)]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->route('admin.users.show', $user)->with('success', __('admin.user_updated'));
    }

    public function toggleBan(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('error', __('admin.cannot_ban_admin'));
        }

        $user->update([
            'banned_at' => $user->banned_at ? null : now(),
        ]);

        $action = $user->banned_at ? 'banned' : 'unbanned';

        return back()->with('success', __('admin.user_'.$action));
    }

    public function adjustBalance(AdjustBalanceRequest $request, User $user): RedirectResponse
    {

        $amount = (int) $request->input('amount');
        $type = $request->input('type');
        $reason = $request->input('reason');

        if ($type === 'deposit') {
            $this->walletService->deposit($user, $amount, 'admin_deposit', $reason);
        } else {
            $this->walletService->deduct($user, $amount, 'admin_withdraw', $reason);
        }

        return back()->with('success', __('admin.balance_adjusted', ['type' => $type, 'amount' => number_format($amount, 0, ',', '.')]));
    }
}
