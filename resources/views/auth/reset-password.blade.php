@extends('layouts.app')

@section('title', __('auth.reset_password_title') . ' - CoLearn')

@section('content')
    <div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl">
            <!-- Header Logo & Title -->
            <div class="text-center">
                <div
                    class="w-14 h-14 bg-gradient-to-tr from-orange-500 to-amber-500 rounded-2xl text-white font-black text-2xl flex items-center justify-center mx-auto shadow-lg shadow-orange-500/20 mb-4"
                >
                    C
                </div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">
                    {{ __('auth.reset_password_title') }}
                </h2>
                <p class="text-xs font-semibold text-slate-500 mt-1">{{ __('auth.reset_password_sub') }}</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" class="mt-8 space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}" />

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            {{ __('auth.email') }}
                        </label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email', $request->email) }}"
                            required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                        />
                        @error('email')
                            <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            {{ __('auth.password_label') }}
                        </label>
                        <input
                            type="password"
                            name="password"
                            required
                            placeholder="{{ __('auth.password_min_placeholder') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                        />
                        @error('password')
                            <p class="text-xs text-rose-500 font-bold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">
                            {{ __('auth.confirm_password') }}
                        </label>
                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="{{ __('auth.confirm_password_placeholder') }}"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition-all"
                        />
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="w-full py-3.5 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-extrabold text-sm rounded-xl shadow-lg shadow-orange-500/25 transition-all"
                    >
                        {{ __('auth.reset_password_title') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
