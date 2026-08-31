<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\UpdatePasswordRequest;
use App\Http\Requests\Teacher\UpdateTeacherProfileRequest;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('teacher.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateTeacherProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update($request->validated());

        return back()->with('status', __('teacher.profile_updated_success'));
    }

    public function updateAvatar(UpdateAvatarRequest $request): RedirectResponse
    {
        $request->validated();

        $user = $request->user();

        // Delete old avatar from storage
        if ($user->avatar) {
            $oldPath = str_replace('/storage/', '', $user->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = Storage::url($path);
        $user->save();

        return back()->with('status', __('teacher.avatar_updated_success'));
    }

    public function updatePassword(UpdatePasswordRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (Hash::check($validated['password'], $request->user()->password)) {
            return back()->withErrors(['password' => __('auth.password_must_differ')]);
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', __('teacher.password_updated_success'));
    }
}
