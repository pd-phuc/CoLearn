<?php

namespace App\Http\Controllers\Concerns;

use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;

trait HandlesProfileActions
{
    public function updateAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $user->avatar));
            }

            $extension = $request->file('avatar')->getClientOriginalExtension() ?: 'png';
            $path = $request->file('avatar')->storeAs('avatars', "{$user->id}.{$extension}", 'public');

            $user->update(['avatar' => Storage::url($path)]);
        }

        return back()->with('status', __('messages.avatar_updated_success'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        Password::broker()->sendResetLink([
            'email' => $user->email,
        ]);

        return back()->with('status', __('messages.password_reset_link_sent', ['email' => $user->email]));
    }
}
