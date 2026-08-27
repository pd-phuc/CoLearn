@extends('layouts.app')

@section('title', __('auth.forgot_password_title') . ' - CoLearn')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50">
        <div
            class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50"
        >
            <!-- Header Logo & Title -->
            <div class="text-center space-y-2">
                <div
                    class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-orange-500 text-white font-extrabold text-2xl shadow-md shadow-orange-500/20 mb-2"
                >
                    C
                </div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                    {{ __('auth.forgot_password_title') }}
                </h2>
                <p class="text-xs font-semibold text-slate-500 leading-relaxed">
                    {{ __('auth.forgot_password_sub') }}
                </p>
            </div>

            <!-- Success Status Alert -->
            <form action="{{ route('password.email') }}" method="POST" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        {{ __('auth.email') }}
                    </label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', request('email')) }}"
                        required
                        autofocus
                        placeholder="{{ __('auth.email_placeholder') }}"
                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all @error('email') border-rose-500 @enderror"
                    />
                    @error('email')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full btn-primary py-3.5 text-sm font-bold shadow-md shadow-orange-500/20"
                    >
                        {{ __('auth.send_reset_link_btn') }}
                    </button>
                </div>
            </form>

            <!-- Back to Login -->
            <div class="text-center pt-2">
                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-xs font-bold text-orange-600 hover:text-orange-700 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    <span>{{ __('auth.back_to_login') }}</span>
                </a>
            </div>
        </div>
    </div>
@endsection
