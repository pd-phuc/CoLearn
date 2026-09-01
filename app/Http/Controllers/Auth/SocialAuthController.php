<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class SocialAuthController extends Controller
{
    public function redirectToProvider(string $provider): RedirectResponse
    {
        if (! in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login');
        }

        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback(string $provider): RedirectResponse
    {
        if (! in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', __('auth.failed'));
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                'email' => $socialUser->getEmail(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
            ]);

            $studentRole = Role::findByName('student');
            if ($studentRole) {
                $user->assignRole($studentRole);
            }

            $this->downloadAvatar($user, $socialUser->getAvatar());
        } else {
            $user->update([
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);

            if (! $user->avatar) {
                $this->downloadAvatar($user, $socialUser->getAvatar());
            }
        }

        if ($user->isBanned()) {
            return redirect()->route('login')->withErrors([
                'email' => __('auth.banned'),
            ]);
        }

        Auth::login($user, true);

        $intended = $user->isAdmin()
            ? route('admin.dashboard')
            : ($user->isTeacher() ? route('teacher.dashboard') : route('home'));

        return redirect()->intended($intended)->with('success', __('auth.welcome_back'));
    }

    /**
     * Download avatar from OAuth provider and store locally.
     */
    private function downloadAvatar(User $user, ?string $url): void
    {
        if (! $url) {
            return;
        }

        try {
            $response = Http::timeout(5)->get($url);

            if (! $response->successful()) {
                return;
            }

            $contentType = $response->header('Content-Type');
            $extension = match (true) {
                str_contains($contentType, 'png') => 'png',
                str_contains($contentType, 'gif') => 'gif',
                str_contains($contentType, 'webp') => 'webp',
                default => 'jpg',
            };

            $path = "avatars/{$user->id}.{$extension}";
            Storage::disk('public')->put($path, $response->body());

            $user->update(['avatar' => Storage::url($path)]);
        } catch (\Exception $e) {
            // Silently fail — user can upload avatar manually later
        }
    }
}
