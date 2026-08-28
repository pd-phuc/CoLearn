<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BannedUserTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
        Role::firstOrCreate(['name' => 'student']);
    }

    public function test_banned_user_cannot_log_in_via_web(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'banned_at' => now(),
        ]);
        $user->assignRole('student');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_unbanned_user_can_log_in_via_web(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'banned_at' => null,
        ]);
        $user->assignRole('student');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
    }

    public function test_banned_user_cannot_log_in_via_api(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'banned_at' => now(),
        ]);
        $user->assignRole('student');

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function test_active_session_is_terminated_when_user_is_banned_mid_session(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'banned_at' => null,
        ]);
        $user->assignRole('student');

        // Log in first — session is valid
        $this->actingAs($user);
        $this->get('/wallet')->assertOk();

        // Admin bans the user mid-session
        $user->update(['banned_at' => now()]);
        $user->refresh();

        // Next request should be terminated
        $response = $this->get('/wallet');
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_banned_error_message_uses_translation_key(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password'),
            'banned_at' => now(),
        ]);
        $user->assignRole('student');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email' => __('auth.banned'),
        ]);
    }
}
