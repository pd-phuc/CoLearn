<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
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

    public function test_teacher_cannot_change_to_same_password(): void
    {
        $password = 'OldPassword123!';
        $teacher = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)
            ->put(route('teacher.profile.password'), [
                'current_password' => $password,
                'password' => $password,
                'password_confirmation' => $password,
            ]);

        $response->assertSessionHasErrors('password');
    }

    public function test_teacher_can_change_to_different_password(): void
    {
        $oldPassword = 'OldPassword123!';
        $newPassword = 'NewPassword456!';
        $teacher = User::factory()->create([
            'password' => Hash::make($oldPassword),
        ]);
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)
            ->put(route('teacher.profile.password'), [
                'current_password' => $oldPassword,
                'password' => $newPassword,
                'password_confirmation' => $newPassword,
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $teacher->refresh();
        $this->assertTrue(Hash::check($newPassword, $teacher->password));
    }
}
