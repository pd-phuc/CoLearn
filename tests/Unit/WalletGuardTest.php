<?php

namespace Tests\Unit;

use App\Exceptions\InsufficientBalanceException;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class WalletGuardTest extends TestCase
{
    use DatabaseTransactions;

    protected WalletService $walletService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletService = app(WalletService::class);
    }

    public function test_deduct_with_insufficient_balance_throws(): void
    {
        $user = User::factory()->create(['balance' => 100]);

        $this->expectException(InsufficientBalanceException::class);

        $this->walletService->deduct($user, 200, 'buy_course');
    }

    public function test_deduct_with_insufficient_balance_leaves_balance_unchanged(): void
    {
        $user = User::factory()->create(['balance' => 100]);

        try {
            $this->walletService->deduct($user, 200, 'buy_course');
        } catch (InsufficientBalanceException) {
            // expected
        }

        $user->refresh();
        $this->assertEquals(100, $user->balance);
    }

    public function test_deduct_with_negative_amount_throws(): void
    {
        $user = User::factory()->create(['balance' => 100]);

        $this->expectException(\InvalidArgumentException::class);

        $this->walletService->deduct($user, -50, 'buy_course');
    }

    public function test_deduct_with_zero_amount_throws(): void
    {
        $user = User::factory()->create(['balance' => 100]);

        $this->expectException(\InvalidArgumentException::class);

        $this->walletService->deduct($user, 0, 'buy_course');
    }

    public function test_deposit_with_negative_amount_throws(): void
    {
        $user = User::factory()->create(['balance' => 100]);

        $this->expectException(\InvalidArgumentException::class);

        $this->walletService->deposit($user, -50, 'deposit_bank');
    }

    public function test_deposit_with_zero_amount_throws(): void
    {
        $user = User::factory()->create(['balance' => 100]);

        $this->expectException(\InvalidArgumentException::class);

        $this->walletService->deposit($user, 0, 'deposit_bank');
    }

    public function test_deduct_with_exact_balance_succeeds(): void
    {
        $user = User::factory()->create(['balance' => 100]);

        $tx = $this->walletService->deduct($user, 100, 'buy_course');

        $user->refresh();
        $this->assertEquals(0, $user->balance);
        $this->assertEquals('out', $tx->type);
        $this->assertEquals(100, $tx->balance_before);
        $this->assertEquals(0, $tx->balance_after);
    }

    public function test_deposit_with_valid_amount_succeeds(): void
    {
        $user = User::factory()->create(['balance' => 100]);

        $tx = $this->walletService->deposit($user, 200, 'deposit_bank');

        $user->refresh();
        $this->assertEquals(300, $user->balance);
        $this->assertEquals('in', $tx->type);
        $this->assertEquals(100, $tx->balance_before);
        $this->assertEquals(300, $tx->balance_after);
    }

    public function test_deduct_creates_transaction_log(): void
    {
        $user = User::factory()->create(['balance' => 500]);

        $tx = $this->walletService->deduct($user, 200, 'buy_course', 'Test purchase');

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'amount' => 200,
            'type' => 'out',
            'action' => 'buy_course',
            'description' => 'Test purchase',
        ]);
    }

    public function test_deposit_updates_total_deposit(): void
    {
        $user = User::factory()->create(['balance' => 0, 'total_deposit' => 0]);

        $this->walletService->deposit($user, 500, 'deposit_bank');

        $user->refresh();
        $this->assertEquals(500, $user->total_deposit);
    }
}
