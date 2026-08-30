<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Deposit money into a user's wallet.
     * Handles locking, validation, balance update, and transaction logging atomically.
     *
     * @throws \InvalidArgumentException if $amount <= 0
     */
    public function deposit(
        User $user,
        int $amount,
        string $action = 'deposit_bank',
        ?string $description = null,
        ?string $orderId = null,
        ?string $referenceId = null,
    ): Transaction {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Deposit amount must be greater than zero.');
        }

        return DB::transaction(function () use ($user, $amount, $action, $description, $orderId, $referenceId) {
            $user = User::lockForUpdate()->findOrFail($user->id);

            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore + $amount;

            $user->balance = $balanceAfter;
            $user->total_deposit = $user->total_deposit + $amount;
            $user->save();

            return Transaction::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'amount' => $amount,
                'type' => 'in',
                'action' => $action,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
                'reference_id' => $referenceId,
            ]);
        });
    }

    /**
     * Deduct money from a user's wallet.
     * Handles locking, validation, balance check, balance update, and transaction logging atomically.
     *
     * @throws \InvalidArgumentException if $amount <= 0
     * @throws InsufficientBalanceException if balance < $amount
     */
    public function deduct(
        User $user,
        int $amount,
        string $action = 'buy_course',
        ?string $description = null,
        ?string $orderId = null,
        ?string $referenceId = null,
    ): Transaction {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Deduct amount must be greater than zero.');
        }

        return DB::transaction(function () use ($user, $amount, $action, $description, $orderId, $referenceId) {
            $user = User::lockForUpdate()->findOrFail($user->id);

            if ($user->balance < $amount) {
                throw new InsufficientBalanceException;
            }

            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore - $amount;

            $user->balance = $balanceAfter;
            $user->save();

            return Transaction::create([
                'user_id' => $user->id,
                'order_id' => $orderId,
                'amount' => $amount,
                'type' => 'out',
                'action' => $action,
                'balance_before' => $balanceBefore,
                'balance_after' => $balanceAfter,
                'description' => $description,
                'reference_id' => $referenceId,
            ]);
        });
    }
}
