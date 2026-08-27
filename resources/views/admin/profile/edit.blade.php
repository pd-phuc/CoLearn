@extends('admin.layouts.admin')

@section('title', __('admin.profile_settings') . ' — CoLearn')

@section('admin-content')
    <div class="py-2">
        <div class="max-w-5xl mx-auto">
            <!-- Header Banner -->
            <div
                class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 mb-8 flex flex-col sm:flex-row items-center gap-6"
            >
                <div class="relative group">
                    @if ($user->avatar)
                        <img
                            src="{{ $user->avatar }}"
                            alt="{{ $user->name }}"
                            class="w-24 h-24 rounded-full object-cover ring-4 ring-orange-500/20 shadow-md"
                        />
                    @else
                        <div
                            class="w-24 h-24 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black text-3xl flex items-center justify-center shadow-md"
                        >
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="text-center sm:text-left flex-1">
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
                    <p class="text-sm font-semibold text-slate-500 mt-1">{{ $user->headline ?? $user->email }}</p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                        <span
                            class="px-3 py-1 bg-purple-100 text-purple-700 font-bold text-xs rounded-full uppercase tracking-wider"
                        >
                            {{ __('admin.system_admin') }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">
                            {{ __('messages.joined_at', ['date' => $user->created_at->format('m/Y')]) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Success Status Alert -->
            <!-- Settings Container with Alpine Tabs -->
            <div
                x-data="{ tab: 'personal' }"
                class="bg-white rounded-3xl shadow-sm border border-slate-200/80 overflow-hidden"
            >
                <!-- Navigation Tabs -->
                <div class="flex border-b border-slate-200 bg-slate-50/50 p-2 gap-2 overflow-x-auto">
                    <button
                        @click="tab = 'personal'"
                        :class="{ 'bg-white text-orange-600 shadow-xs border-slate-200': tab === 'personal', 'text-slate-600 hover:text-slate-900': tab !== 'personal' }"
                        class="px-5 py-3 rounded-2xl text-sm font-bold transition-all border border-transparent flex items-center gap-2 shrink-0"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            />
                        </svg>
                        {{ __('messages.tab_personal_info') }}
                    </button>

                    <button
                        @click="tab = 'avatar'"
                        :class="{ 'bg-white text-orange-600 shadow-xs border-slate-200': tab === 'avatar', 'text-slate-600 hover:text-slate-900': tab !== 'avatar' }"
                        class="px-5 py-3 rounded-2xl text-sm font-bold transition-all border border-transparent flex items-center gap-2 shrink-0"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>
                        {{ __('messages.tab_avatar') }}
                    </button>

                    <button
                        @click="tab = 'security'"
                        :class="{ 'bg-white text-orange-600 shadow-xs border-slate-200': tab === 'security', 'text-slate-600 hover:text-slate-900': tab !== 'security' }"
                        class="px-5 py-3 rounded-2xl text-sm font-bold transition-all border border-transparent flex items-center gap-2 shrink-0"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>
                        {{ __('messages.tab_security') }}
                    </button>
                </div>

                <!-- TAB 1: Personal Info -->
                <div x-show="tab === 'personal'" class="p-6 sm:p-8 space-y-6">
                    <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    {{ __('messages.full_name_label') }}
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                                />
                                @error('name')
                                    <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    {{ __('messages.email_label') }}
                                </label>
                                <input
                                    type="email"
                                    value="{{ $user->email }}"
                                    disabled
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold bg-slate-100 text-slate-400 cursor-not-allowed"
                                />
                                <p class="text-[11px] text-slate-400 mt-1">
                                    {{ __('messages.email_protected_notice') }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    {{ __('messages.headline_label') }}
                                </label>
                                <input
                                    type="text"
                                    name="headline"
                                    value="{{ old('headline', $user->headline) }}"
                                    placeholder="{{ __('messages.headline_placeholder') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                                />
                                @error('headline')
                                    <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                    {{ __('messages.phone_label') }}
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    placeholder="{{ __('messages.phone_placeholder') }}"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                                />
                                @error('phone')
                                    <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                                {{ __('messages.bio_label') }}
                            </label>
                            <textarea
                                name="bio"
                                rows="4"
                                placeholder="{{ __('messages.bio_placeholder') }}"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                            >
{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="border-t border-slate-100 pt-6">
                            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider mb-4">
                                {{ __('messages.social_links') }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-2">
                                        {{ __('messages.github_label') }}
                                    </label>
                                    <input
                                        type="url"
                                        name="github_url"
                                        value="{{ old('github_url', $user->github_url) }}"
                                        placeholder="https://github.com/username"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                                    />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-2">
                                        {{ __('messages.linkedin_label') }}
                                    </label>
                                    <input
                                        type="url"
                                        name="linkedin_url"
                                        value="{{ old('linkedin_url', $user->linkedin_url) }}"
                                        placeholder="https://linkedin.com/in/username"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                                    />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 mb-2">
                                        {{ __('messages.facebook_label') }}
                                    </label>
                                    <input
                                        type="url"
                                        name="facebook_url"
                                        value="{{ old('facebook_url', $user->facebook_url) }}"
                                        placeholder="https://facebook.com/username"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button
                                type="submit"
                                class="px-8 py-3.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-orange-500/25 transition-all"
                            >
                                {{ __('messages.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: Upload Avatar -->
                <div x-show="tab === 'avatar'" class="p-6 sm:p-8 space-y-6" style="display: none">
                    <div class="max-w-xl mx-auto text-center space-y-6">
                        <h3 class="text-xl font-black text-slate-900">{{ __('messages.upload_avatar_title') }}</h3>
                        <p class="text-sm font-semibold text-slate-500">{{ __('messages.upload_avatar_sub') }}</p>

                        <form
                            action="{{ route('admin.profile.avatar') }}"
                            method="POST"
                            enctype="multipart/form-data"
                            x-data="{ photoName: null, photoPreview: null }"
                            class="space-y-6"
                        >
                            @csrf

                            <div class="flex flex-col items-center justify-center">
                                <!-- Image Preview Container (Clickable) -->
                                <div class="relative group cursor-pointer mb-4" @click="$refs.photo.click()">
                                    <template x-if="!photoPreview">
                                        @if ($user->avatar)
                                            <img
                                                src="{{ $user->avatar }}"
                                                alt="{{ $user->name }}"
                                                class="w-32 h-32 rounded-full object-cover ring-4 ring-orange-500/20 shadow-lg group-hover:opacity-80 transition-opacity"
                                            />
                                        @else
                                            <div
                                                class="w-32 h-32 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black text-4xl flex items-center justify-center shadow-lg group-hover:opacity-80 transition-opacity"
                                            >
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </template>
                                    <template x-if="photoPreview">
                                        <span
                                            class="block w-32 h-32 rounded-full bg-cover bg-no-repeat bg-center ring-4 ring-orange-500/30 shadow-lg"
                                            :style="'background-image: url(\'' + photoPreview + '\');'"
                                        ></span>
                                    </template>
                                    <div
                                        class="absolute inset-0 rounded-full bg-black/40 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                    >
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Hidden File Input -->
                                <input
                                    type="file"
                                    name="avatar"
                                    id="avatar"
                                    class="hidden"
                                    x-ref="photo"
                                    @change="
                                        if ($refs.photo.files[0]) {
                                            photoName = $refs.photo.files[0].name;
                                            const reader = new FileReader();
                                            reader.onload = (e) => { photoPreview = e.target.result; };
                                            reader.readAsDataURL($refs.photo.files[0]);
                                            $el.closest('form').submit();
                                        }
                                   "
                                />

                                @error('avatar')
                                    <p class="text-xs text-rose-500 font-bold mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Single Orange Action Button -->
                            <div class="pt-2">
                                <button
                                    type="button"
                                    @click.prevent="$refs.photo.click()"
                                    class="px-8 py-3.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-orange-500/25 transition-all flex items-center justify-center gap-2 mx-auto cursor-pointer"
                                >
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                        />
                                    </svg>
                                    <span>{{ __('messages.upload_avatar_btn') }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- TAB 3: Security & Password -->
                <div x-show="tab === 'security'" class="p-6 sm:p-8 space-y-6" style="display: none">
                    <div class="max-w-xl mx-auto space-y-6 text-center">
                        <div
                            class="w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-2"
                        >
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                />
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-xl font-black text-slate-900">{{ __('messages.change_password') }}</h3>
                            <p class="text-sm font-semibold text-slate-500 mt-2 leading-relaxed">
                                {{ __('messages.password_notice') }}
                            </p>
                            <div
                                class="mt-4 p-3 bg-slate-100/80 rounded-xl text-xs font-bold text-slate-700 inline-block border border-slate-200"
                            >
                                Email:
                                <span class="text-orange-600 font-extrabold">{{ $user->email }}</span>
                            </div>
                        </div>

                        <form action="{{ route('admin.profile.password') }}" method="POST" class="pt-2">
                            @csrf
                            @method('PUT')

                            <button
                                type="submit"
                                class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-orange-500/25 transition-all flex items-center justify-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                                <span>{{ __('messages.send_password_reset_link') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
