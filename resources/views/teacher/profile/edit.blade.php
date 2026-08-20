@extends('teacher.layouts.teacher')

@section('teacher-content')
<div class="max-w-4xl mx-auto space-y-8">

    {{-- Header Banner --}}
    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xs border border-slate-200/80 flex flex-col sm:flex-row items-center gap-6">
        <div class="relative group">
            <x-user-avatar :user="$user" size="2xl" class="ring-4 ring-blue-500/20 shadow-md" />
        </div>

        <div class="text-center sm:text-left flex-1">
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">{{ $user->name }}</h1>
            <p class="text-sm font-semibold text-slate-500 mt-1">{{ $user->headline ?? $user->email }}</p>
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 font-bold text-xs rounded-full uppercase tracking-wider">{{ __('teacher.teacher_role') }}</span>
                <span class="text-xs text-slate-400 font-medium">{{ __('messages.joined_at', ['date' => $user->created_at->format('m/Y')]) }}</span>
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

                <form action="{{ route('teacher.profile.avatar') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <input type="file" name="avatar" required accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-colors">
                        {{ __('teacher.upload_new_avatar') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Info Section --}}
        <div class="md:col-span-2 space-y-6">
            <form action="{{ route('teacher.profile.update') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
                @csrf
                @method('PUT')

                <h3 class="text-base font-black text-slate-900 border-b border-slate-100 pb-3">{{ __('teacher.teacher_info_title') }}</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('teacher.full_name') }} *</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                        @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('teacher.email') }} *</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                        @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('teacher.headline') }}</label>
                    <input type="text" name="headline" value="{{ old('headline', $user->headline) }}" placeholder="{{ __('teacher.headline_placeholder') }}" class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('teacher.bio') }}</label>
                    <textarea name="bio" rows="4" placeholder="{{ __('teacher.bio_placeholder') }}" class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">{{ old('bio', $user->bio) }}</textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                        {{ __('teacher.save_changes') }}
                    </button>
                </div>
            </form>

            {{-- Password Change Form --}}
            <form action="{{ route('teacher.profile.password') }}" method="POST" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-4">
                @csrf
                @method('PUT')

                <h3 class="text-base font-black text-slate-900 border-b border-slate-100 pb-3">{{ __('teacher.change_password') }}</h3>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('teacher.current_password') }} *</label>
                    <input type="password" name="current_password" required class="w-full px-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                    @error('current_password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('teacher.new_password') }} *</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                        @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">{{ __('teacher.confirm_new_password') }} *</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all">
                        {{ __('teacher.update_password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
