<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Services\SePayService;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderExpiryTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        Role::firstOrCreate(['name' => 'student']);
        $this->user = User::factory()->create();
        $this->user->assignRole('student');
    }

    private function createOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_number' => 'ORD-TOPUP-'.date('Ymd').'-TEST1',
            'order_type' => 'topup',
            'user_id' => $this->user->id,
            'subtotal' => 100000,
            'discount_amount' => 0,
            'total_amount' => 100000,
            'status' => 'pending',
            'payment_method' => 'sepay',
            'expires_at' => now()->addMinutes(15),
        ], $overrides));
    }

    public function test_expired_order_webhook_is_rejected(): void
    {
        $order = $this->createOrder([
            'expires_at' => now()->subMinutes(1),
        ]);

        $sePayService = app(SePayService::class);
        $result = $sePayService->processWebhookPayload([
            'transferType' => 'in',
            'content' => $order->order_number,
            'transferAmount' => 100000,
        ]);

        $this->assertFalse($result['success']);
        $this->assertStringContainsString('expired', $result['message']);

        $order->refresh();
        $this->assertNotEquals('paid', $order->status);
    }

    public function test_non_expired_order_webhook_is_accepted(): void
    {
        $order = $this->createOrder([
            'expires_at' => now()->addMinutes(10),
        ]);

        $sePayService = app(SePayService::class);
        $result = $sePayService->processWebhookPayload([
            'transferType' => 'in',
            'content' => $order->order_number,
            'transferAmount' => 100000,
        ]);

        $this->assertTrue($result['success']);

        $order->refresh();
        $this->assertEquals('paid', $order->status);
    }

    public function test_expire_stale_orders_command(): void
    {
        $expiredOrder = $this->createOrder([
            'order_number' => 'ORD-TOPUP-'.date('Ymd').'-EXP01',
            'expires_at' => now()->subMinutes(5),
        ]);

        $activeOrder = $this->createOrder([
            'order_number' => 'ORD-TOPUP-'.date('Ymd').'-ACT01',
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->artisan('orders:expire-stale')->assertSuccessful();

        $expiredOrder->refresh();
        $activeOrder->refresh();

        $this->assertEquals('expired', $expiredOrder->status);
        $this->assertEquals('pending', $activeOrder->status);
    }

    public function test_is_expired_returns_correct_values(): void
    {
        $expired = $this->createOrder(['expires_at' => now()->subSecond()]);
        $active = $this->createOrder([
            'order_number' => 'ORD-TOPUP-'.date('Ymd').'-ACT02',
            'expires_at' => now()->addMinutes(10),
        ]);
        $noExpiry = $this->createOrder([
            'order_number' => 'ORD-TOPUP-'.date('Ymd').'-NOEX',
            'expires_at' => null,
        ]);

        $this->assertTrue($expired->isExpired());
        $this->assertFalse($active->isExpired());
        $this->assertFalse($noExpiry->isExpired());
    }

    public function test_viewing_expired_topup_redirects(): void
    {
        $order = $this->createOrder([
            'expires_at' => now()->subMinutes(1),
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('wallet.topup.show', $order));

        $response->assertRedirect(route('wallet.index'));
    }
}
