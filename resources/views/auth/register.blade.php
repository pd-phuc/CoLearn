@extends('layouts.app')

@section('content')
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
        <div
            class="max-w-md w-full space-y-8 bg-white p-8 rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100"
        >
            <!-- Header -->
            <div class="text-center space-y-2">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-orange-500 text-white font-extrabold text-2xl shadow-md shadow-orange-500/20 mb-2"
                >
                    C
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                    {{ __('auth.create_account') }}
                </h2>
                <p class="text-xs font-medium text-slate-500">
                    {{ __('auth.join_colearn_sub') }}
                </p>
            </div>

            <!-- Social OAuth Registration Pills -->
            <div class="space-y-3">
                <p class="text-xs font-semibold text-slate-400 text-center uppercase tracking-wider">
                    {{ __('auth.quick_social_register') }}
                </p>
                <div class="grid grid-cols-2 gap-3">
                    <a
                        href="{{ route('social.redirect', 'google') }}"
                        class="btn-oauth-icon group"
                        title="{{ __('auth.social_login_google') }}"
                    >
                        <svg class="w-6 h-6 transition-transform group-hover:scale-110" viewBox="0 0 24 24">
                            <path
                                fill="#4285F4"
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            />
                            <path
                                fill="#34A853"
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            />
                            <path
                                fill="#FBBC05"
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"
                            />
                            <path
                                fill="#EA4335"
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"
                            />
                        </svg>
                        <span class="sr-only">Google</span>
                    </a>

                    <a
                        href="{{ route('social.redirect', 'facebook') }}"
                        class="btn-oauth-icon group"
                        title="{{ __('auth.social_login_facebook') }}"
                    >
                        <svg
                            class="w-6 h-6 text-[#1877F2] fill-current transition-transform group-hover:scale-110"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                            />
                        </svg>
                        <span class="sr-only">Facebook</span>
                    </a>
                </div>
            </div>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white px-3 text-slate-400 font-semibold tracking-wider">
                        {{ __('auth.or_register_with') }}
                    </span>
                </div>
            </div>

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('auth.name') }}
                    </label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        placeholder="{{ __('auth.name_placeholder') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all @error('name') border-rose-500 @enderror"
                    />
                    @error('name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('auth.email') }}
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        placeholder="{{ __('auth.email_placeholder') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all @error('email') border-rose-500 @enderror"
                    />
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('auth.password_label') }}
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        placeholder="{{ __('auth.password_min_placeholder') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all @error('password') border-rose-500 @enderror"
                    />
                    @error('password')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label
                        for="password_confirmation"
                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1"
                    >
                        {{ __('auth.confirm_password') }}
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        placeholder="{{ __('auth.confirm_password_placeholder') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all"
                    />
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full btn-primary py-3 text-base font-bold shadow-md shadow-orange-500/20 mt-2"
                >
                    {{ __('auth.register') }}
                </button>
            </form>

            <!-- Login Footer Link -->
            <div class="text-center pt-2">
                <p class="text-xs text-slate-500">
                    {{ __('auth.already_have_account') }}
                    <a href="{{ route('login') }}" class="font-bold text-orange-600 hover:text-orange-700 ml-1">
                        {{ __('auth.login_now') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
