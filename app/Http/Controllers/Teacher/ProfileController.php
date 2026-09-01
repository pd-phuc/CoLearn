<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Concerns\HandlesProfileActions;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use HandlesProfileActions;

    public function edit(Request $request): View
    {
        return view('teacher.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return back()->with('status', __('teacher.profile_updated_success'));
    }
}
