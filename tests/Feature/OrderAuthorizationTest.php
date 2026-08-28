<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OrderAuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    protected User $owner;

    protected User $stranger;

    protected Order $order;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);

        Role::firstOrCreate(['name' => 'student']);

        $this->owner = User::factory()->create();
        $this->owner->assignRole('student');

        $this->stranger = User::factory()->create();
        $this->stranger->assignRole('student');

        $this->order = Order::create([
            'order_number' => 'ORD-TEST-001',
            'order_type' => 'topup',
            'user_id' => $this->owner->id,
            'subtotal' => 100000,
            'discount_amount' => 0,
            'total_amount' => 100000,
            'status' => 'pending',
            'payment_method' => 'sepay',
        ]);
    }

    public function test_owner_can_poll_order_status(): void
    {
        $response = $this->actingAs($this->owner)
            ->getJson(route('orders.status', $this->order));

        $response->assertOk();
        $response->assertJsonStructure(['status', 'paid', 'redirect']);
    }

    public function test_stranger_cannot_poll_order_status(): void
    {
        $response = $this->actingAs($this->stranger)
            ->getJson(route('orders.status', $this->order));

        $response->assertForbidden();
    }

    public function test_owner_can_view_order_detail(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('orders.show', $this->order));

        $response->assertOk();
    }

    public function test_stranger_cannot_view_order_detail(): void
    {
        $response = $this->actingAs($this->stranger)
            ->get(route('orders.show', $this->order));

        $response->assertForbidden();
    }

    public function test_owner_can_view_pending_topup(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('wallet.topup.show', $this->order));

        $response->assertOk();
    }

    public function test_stranger_cannot_view_pending_topup(): void
    {
        $response = $this->actingAs($this->stranger)
            ->get(route('wallet.topup.show', $this->order));

        $response->assertForbidden();
    }

    public function test_guest_cannot_poll_order_status(): void
    {
        $response = $this->get(route('orders.status', $this->order));

        $response->assertRedirect(route('login'));
    }
}
