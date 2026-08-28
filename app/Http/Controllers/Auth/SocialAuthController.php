<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
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
                'avatar' => $socialUser->getAvatar(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'email_verified_at' => now(),
            ]);

            $studentRole = Role::findByName('student');
            if ($studentRole) {
                $user->assignRole($studentRole);
            }
        } else {
            $user->update([
                'avatar' => $socialUser->getAvatar() ?? $user->avatar,
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
            ]);
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
}
