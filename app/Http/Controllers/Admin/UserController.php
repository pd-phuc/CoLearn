<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
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

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update($request->only(['name', 'email']));

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated.');
    }

    public function toggleBan(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot ban an admin user.');
        }

        $user->update([
            'banned_at' => $user->banned_at ? null : now(),
        ]);

        $action = $user->banned_at ? 'banned' : 'unbanned';

        return back()->with('success', "User {$action}.");
    }

    public function adjustBalance(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'type' => ['required', 'in:deposit,withdraw'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $amount = (float) $request->input('amount');
        $type = $request->input('type');
        $reason = $request->input('reason');

        DB::transaction(function () use ($user, $amount, $type, $reason) {
            $user = User::lockForUpdate()->find($user->id);

            if ($type === 'deposit') {
                $user->deposit($amount, 'admin_deposit', $reason);
            } else {
                if ($user->balance < $amount) {
                    throw new \RuntimeException('Insufficient balance.');
                }
                $user->deduct($amount, 'admin_withdraw', $reason);
            }
        });

        return back()->with('success', ucfirst($type)." of {$amount} completed.");
    }
}
