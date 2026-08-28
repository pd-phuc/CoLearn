<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\SettingService;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SePayWebhookAuthTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);

        Role::firstOrCreate(['name' => 'student']);

        $this->user = User::factory()->create(['balance' => 0.00]);
        $this->user->assignRole('student');

        $this->order = Order::create([
            'order_number' => 'ORD-TOPUP-20260827-AUTH1',
            'order_type' => 'topup',
            'user_id' => $this->user->id,
            'subtotal' => 300000,
            'discount_amount' => 0,
            'total_amount' => 300000,
            'status' => 'pending',
            'payment_method' => 'sepay',
        ]);
    }

    /**
     * A payload that would be fulfilled if it passed authentication, so a rejection
     * proves the auth branch stopped it rather than a malformed body.
     */
    private function validPayload(): array
    {
        return [
            'id' => 424242,
            'gateway' => 'MBBank',
            'transferType' => 'in',
            'transferAmount' => 300000,
            'content' => 'Thanh toan don hang '.$this->order->order_number,
        ];
    }

    private function withoutConfiguredApiKey(): void
    {
        Setting::where('group', 'sepay')->where('key', 'api_key')->delete();
        config(['services.sepay.api_key' => null]);
        Cache::flush();
    }

    private function withConfiguredApiKey(string $key): void
    {
        Cache::flush();
        app(SettingService::class)->set('sepay', 'api_key', $key);
    }

    private function runningIn(string $environment): void
    {
        $this->app->detectEnvironment(fn () => $environment);
    }

    private function assertOrderWasNotFulfilled(): void
    {
        $this->assertSame('pending', $this->order->fresh()->status);
        $this->assertNull($this->order->fresh()->paid_at);
        $this->assertEquals(0, $this->user->fresh()->balance);
        $this->assertDatabaseMissing('transactions', ['order_id' => $this->order->id]);
    }

    public function test_production_rejects_the_webhook_when_no_api_key_is_configured(): void
    {
        $this->withoutConfiguredApiKey();
        $this->runningIn('production');

        $response = $this->postJson(route('payment.sepay.webhook'), $this->validPayload());

        $response->assertStatus(401);
        $this->assertOrderWasNotFulfilled();
    }

    public function test_staging_rejects_the_webhook_when_no_api_key_is_configured(): void
    {
        $this->withoutConfiguredApiKey();
        $this->runningIn('staging');

        $response = $this->postJson(route('payment.sepay.webhook'), $this->validPayload());

        $response->assertStatus(401);
        $this->assertOrderWasNotFulfilled();
    }

    public function test_local_still_accepts_the_webhook_without_an_api_key(): void
    {
        $this->withoutConfiguredApiKey();
        $this->runningIn('local');

        $response = $this->postJson(route('payment.sepay.webhook'), $this->validPayload());

        $response->assertStatus(200);
        $this->assertSame('paid', $this->order->fresh()->status);
        $this->assertEquals(300000, $this->user->fresh()->balance);
    }

    public function test_production_rejects_an_incorrect_api_key(): void
    {
        $this->withConfiguredApiKey('the_real_sepay_key');
        $this->runningIn('production');

        $response = $this->postJson(route('payment.sepay.webhook'), $this->validPayload(), [
            'Authorization' => 'Apikey not_the_real_key',
        ]);

        $response->assertStatus(401);
        $this->assertOrderWasNotFulfilled();
    }

    public function test_production_rejects_a_request_with_no_authorization_header(): void
    {
        $this->withConfiguredApiKey('the_real_sepay_key');
        $this->runningIn('production');

        $response = $this->postJson(route('payment.sepay.webhook'), $this->validPayload());

        $response->assertStatus(401);
        $this->assertOrderWasNotFulfilled();
    }

    public function test_production_accepts_the_correct_api_key(): void
    {
        $this->withConfiguredApiKey('the_real_sepay_key');
        $this->runningIn('production');

        $response = $this->postJson(route('payment.sepay.webhook'), $this->validPayload(), [
            'Authorization' => 'Apikey the_real_sepay_key',
        ]);

        $response->assertStatus(200);
        $this->assertSame('paid', $this->order->fresh()->status);
        $this->assertEquals(300000, $this->user->fresh()->balance);
    }
}
