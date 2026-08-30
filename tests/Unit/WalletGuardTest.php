<?php

namespace Tests\Unit;

use App\Exceptions\InsufficientBalanceException;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WalletGuardTest extends TestCase
{
    use DatabaseTransactions;

    private function createUserWithBalance(float $balance): User
    {
        $user = User::factory()->create(['balance' => $balance]);

        return $user;
    }

    public function test_deduct_with_insufficient_balance_throws(): void
    {
        $user = $this->createUserWithBalance(100);

        $this->expectException(InsufficientBalanceException::class);

        $user->deduct(200);
    }

    public function test_deduct_with_insufficient_balance_leaves_balance_unchanged(): void
    {
        $user = $this->createUserWithBalance(100);

        try {
            $user->deduct(200);
        } catch (InsufficientBalanceException) {
            // expected
        }

        $user->refresh();
        $this->assertEquals(100, (float) $user->balance);
    }

    public function test_deduct_with_negative_amount_throws(): void
    {
        $user = $this->createUserWithBalance(100);

        $this->expectException(\InvalidArgumentException::class);

        $user->deduct(-50);
    }

    public function test_deduct_with_zero_amount_throws(): void
    {
        $user = $this->createUserWithBalance(100);

        $this->expectException(\InvalidArgumentException::class);

        $user->deduct(0);
    }

    public function test_deposit_with_negative_amount_throws(): void
    {
        $user = $this->createUserWithBalance(100);

        $this->expectException(\InvalidArgumentException::class);

        $user->deposit(-50);
    }

    public function test_deposit_with_zero_amount_throws(): void
    {
        $user = $this->createUserWithBalance(100);

        $this->expectException(\InvalidArgumentException::class);

        $user->deposit(0);
    }

    public function test_deduct_with_exact_balance_succeeds(): void
    {
        $user = $this->createUserWithBalance(500);

        $transaction = $user->deduct(500);

        $user->refresh();
        $this->assertEquals(0, (float) $user->balance);
        $this->assertEquals('out', $transaction->type);
        $this->assertEquals(500, (float) $transaction->amount);
    }

    public function test_deposit_with_valid_amount_succeeds(): void
    {
        $user = $this->createUserWithBalance(100);

        $transaction = $user->deposit(200);

        $user->refresh();
        $this->assertEquals(300, (float) $user->balance);
        $this->assertEquals('in', $transaction->type);
        $this->assertEquals(200, (float) $transaction->amount);
    }
}
