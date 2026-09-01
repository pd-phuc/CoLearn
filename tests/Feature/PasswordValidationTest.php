<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PasswordValidationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
        Role::firstOrCreate(['name' => 'teacher']);
    }

    public function test_teacher_password_reset_sends_email_link(): void
    {
        Password::shouldReceive('broker')->once()->andReturnSelf();
        Password::shouldReceive('sendResetLink')
            ->once()
            ->with(['email' => 'teacher@example.com'])
            ->andReturn(Password::RESET_LINK_SENT);

        $teacher = User::factory()->create(['email' => 'teacher@example.com']);
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)
            ->put(route('teacher.profile.password'));

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_teacher_password_reset_redirects_back(): void
    {
        Password::shouldReceive('broker')->once()->andReturnSelf();
        Password::shouldReceive('sendResetLink')
            ->once()
            ->andReturn(Password::RESET_LINK_SENT);

        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)
            ->from(route('teacher.profile.edit'))
            ->put(route('teacher.profile.password'));

        $response->assertRedirect(route('teacher.profile.edit'));
    }
}
