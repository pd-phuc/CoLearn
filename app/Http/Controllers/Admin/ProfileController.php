<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the admin profile edit form.
     */
    public function edit(): View
    {
        $user = Auth::user();

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the admin's personal profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'headline' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.profile.edit')
            ->with('status', __('messages.profile_updated_success'));
    }

    /**
     * Upload and update admin avatar image.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension() ?: 'png';
            $filename = "{$user->id}.{$extension}";

            // Move uploaded image to public/images/avatars/
            $destinationPath = public_path('images/avatars');
            if (! file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);

            // Update user avatar attribute
            $user->update([
                'avatar' => "/images/avatars/{$filename}",
            ]);
        }

        return redirect()->route('admin.profile.edit')
            ->with('status', __('messages.avatar_updated_success'));
    }

    /**
     * Send password reset link via email for security.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::user();

        Password::broker()->sendResetLink([
            'email' => $user->email,
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('status', __('messages.password_reset_link_sent', ['email' => $user->email]));
    }
}
