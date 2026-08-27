<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FlashToastTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'admin']);
    }

    private function userWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }

    /**
     * Counting the toast container also guards against a page rendering its own
     * flash block on top of the one its layout already provides.
     */
    private function toastContainers(string $html): int
    {
        return substr_count($html, 'aria-live="polite"');
    }

    public function test_main_layout_renders_a_success_toast(): void
    {
        $response = $this->withSession(['success' => 'Saved successfully'])
            ->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Saved successfully', false);
        $this->assertSame(1, $this->toastContainers($response->getContent()));
    }

    public function test_main_layout_renders_the_status_key(): void
    {
        $response = $this->withSession(['status' => 'Password updated'])
            ->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Password updated', false);
    }

    public function test_admin_layout_renders_a_toast(): void
    {
        $response = $this->actingAs($this->userWithRole('admin'))
            ->withSession(['success' => 'Course approved'])
            ->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Course approved', false);
        $this->assertSame(1, $this->toastContainers($response->getContent()));
    }

    public function test_teacher_layout_renders_a_toast(): void
    {
        $response = $this->actingAs($this->userWithRole('teacher'))
            ->withSession(['status' => 'Course created'])
            ->get(route('teacher.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Course created', false);
        $this->assertSame(1, $this->toastContainers($response->getContent()));
    }

    public function test_cart_page_renders_the_toast_only_once(): void
    {
        $response = $this->withSession(['error' => 'Cart is empty'])
            ->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Cart is empty', false);
        $this->assertSame(1, $this->toastContainers($response->getContent()));
    }

    public function test_several_flash_keys_stack_into_separate_toasts(): void
    {
        $response = $this->withSession(['success' => 'First one', 'error' => 'Second one'])
            ->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('First one', false);
        $response->assertSee('Second one', false);
        $this->assertSame(1, $this->toastContainers($response->getContent()));
    }

    public function test_no_toast_markup_without_a_flash_message(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $this->assertSame(0, $this->toastContainers($response->getContent()));
    }
}
