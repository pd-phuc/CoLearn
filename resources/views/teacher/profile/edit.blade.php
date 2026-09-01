@extends('teacher.layouts.teacher')

@section('teacher-content')
    <div class="max-w-4xl mx-auto space-y-8">
        {{-- Header Banner --}}
        <div
            class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row items-center gap-6"
        >
            <div class="relative group">
                <x-user-avatar :user="$user" size="2xl" class="ring-4 ring-blue-500/20 shadow-md" />
            </div>

            <div class="text-center sm:text-left flex-1">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                <p class="text-sm font-semibold text-slate-500 mt-1">{{ $user->headline ?? $user->email }}</p>
                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                    <span
                        class="px-3 py-1 bg-blue-100 text-blue-700 font-bold text-xs rounded-full uppercase tracking-wider"
                    >
                        {{ __('teacher.teacher_role') }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">
                        {{ __('messages.joined_at', ['date' => $user->created_at->format('m/Y')]) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Form Sections --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Avatar Section --}}
            <div class="md:col-span-1">
                <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs space-y-4">
                    <h3 class="text-sm font-black text-slate-900">{{ __('teacher.avatar_title') }}</h3>
                    <p class="text-xs text-slate-500">{{ __('teacher.avatar_desc') }}</p>

                    <form
                        action="{{ route('teacher.profile.avatar') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="space-y-4"
                    >
                        @csrf
                        <input
                            type="file"
                            name="avatar"
                            required
                            accept="image/*"
                            class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        />
                        <x-button variant="blue" size="sm" type="submit" class="w-full">
                            {{ __('teacher.upload_new_avatar') }}
                        </x-button>
                    </form>
                </div>
            </div>

            {{-- Main Info Section --}}
            <div class="md:col-span-2 space-y-6">
                <form
                    action="{{ route('teacher.profile.update') }}"
                    method="POST"
                    class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6"
                >
                    @csrf
                    @method('PUT')

                    <h3 class="text-base font-black text-slate-900 border-b border-slate-100 pb-3">
                        {{ __('teacher.teacher_info_title') }}
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                {{ __('teacher.full_name') }} *
                            </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium"
                            />
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                {{ __('teacher.email') }}
                            </label>
                            <input
                                type="email"
                                value="{{ $user->email }}"
                                disabled
                                class="w-full px-4 py-2.5 text-xs bg-slate-100 border border-slate-200 rounded-xl font-medium text-slate-400 cursor-not-allowed"
                            />
                            <p class="text-[11px] text-slate-400 mt-1">
                                {{ __('messages.email_protected_notice') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                {{ __('teacher.headline') }}
                            </label>
                            <input
                                type="text"
                                name="headline"
                                value="{{ old('headline', $user->headline) }}"
                                placeholder="{{ __('teacher.headline_placeholder') }}"
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium"
                            />
                            @error('headline')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">
                                {{ __('messages.phone_label') }}
                            </label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="{{ __('messages.phone_placeholder') }}"
                                class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium"
                            />
                            @error('phone')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('teacher.bio') }}</label>
                        <textarea
                            name="bio"
                            rows="4"
                            placeholder="{{ __('teacher.bio_placeholder') }}"
                            class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium"
                        >
{{ old('bio', $user->bio) }}</textarea>
                        @error('bio')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Social Links --}}
                    <div class="border-t border-slate-100 pt-4">
                        <h4 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-3">
                            {{ __('messages.social_links') }}
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">
                                    {{ __('messages.github_label') }}
                                </label>
                                <input
                                    type="url"
                                    name="github_url"
                                    value="{{ old('github_url', $user->github_url) }}"
                                    placeholder="https://github.com/username"
                                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium"
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">
                                    {{ __('messages.linkedin_label') }}
                                </label>
                                <input
                                    type="url"
                                    name="linkedin_url"
                                    value="{{ old('linkedin_url', $user->linkedin_url) }}"
                                    placeholder="https://linkedin.com/in/username"
                                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium"
                                />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">
                                    {{ __('messages.facebook_label') }}
                                </label>
                                <input
                                    type="url"
                                    name="facebook_url"
                                    value="{{ old('facebook_url', $user->facebook_url) }}"
                                    placeholder="https://facebook.com/username"
                                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <x-button variant="blue" size="sm" type="submit">
                            {{ __('teacher.save_changes') }}
                        </x-button>
                    </div>
                </form>

                {{-- Password Reset Section --}}
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                    @csrf

                    <h3 class="text-base font-black text-slate-900 border-b border-slate-100 pb-3">
                        {{ __('teacher.change_password') }}
                    </h3>

                    <p class="text-xs text-slate-500 leading-relaxed">
                        {{ __('messages.password_notice') }}
                    </p>

                    <div
                        class="p-3 bg-slate-100/80 rounded-xl text-xs font-bold text-slate-700 inline-block border border-slate-200"
                    >
                        Email:
                        <span class="text-blue-600 font-extrabold">{{ $user->email }}</span>
                    </div>

                    <form action="{{ route('teacher.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <x-button variant="blue" size="sm" type="submit" class="w-full sm:w-auto">
                            {{ __('messages.send_password_reset_link') }}
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
