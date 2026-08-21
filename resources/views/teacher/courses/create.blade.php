@extends('teacher.layouts.teacher')

@section('teacher-content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Back link & Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('teacher.courses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 mb-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                {{ __('teacher.back_to_courses') }}
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('teacher.create_course') }}</h1>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('teacher.courses.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        @csrf

        {{-- Course Title --}}
        <div>
            <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                {{ __('teacher.course_title') }} <span class="text-rose-500">*</span>
            </label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="{{ __('teacher.course_title_placeholder') }}"
                   class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
            @error('title') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Category & Level Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Category --}}
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.category') }} <span class="text-rose-500">*</span>
                </label>
                <select name="category_id" required class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-semibold text-slate-700">
                    <option value="">{{ __('teacher.select_category') }}</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Skill Level --}}
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.level') }} <span class="text-rose-500">*</span>
                </label>
                <select name="level" required class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-semibold text-slate-700">
                    <option value="all" {{ old('level') == 'all' ? 'selected' : '' }}>{{ __('teacher.level_all') }}</option>
                    <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>{{ __('teacher.level_beginner') }}</option>
                    <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>{{ __('teacher.level_intermediate') }}</option>
                    <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>{{ __('teacher.level_advanced') }}</option>
                </select>
                @error('level') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Pricing Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- Price --}}
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.price') }} <span class="text-rose-500">*</span>
                </label>
                <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="1000" required placeholder="599000"
                       class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-bold">
                <p class="text-[11px] text-slate-400 mt-1 font-medium">{{ __('teacher.price_hint') }}</p>
                @error('price') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Discount Price --}}
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.discount_price') }}
                </label>
                <input type="number" name="discount_price" value="{{ old('discount_price') }}" min="0" step="1000" placeholder="{{ __('teacher.discount_price_placeholder') }}"
                       class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-bold text-blue-600">
                @error('discount_price') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Thumbnail Upload --}}
        <div>
            <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                {{ __('teacher.thumbnail') }}
            </label>
            <input type="file" name="thumbnail" accept="image/*"
                   class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-[11px] text-slate-400 mt-1 font-medium">{{ __('teacher.thumbnail_hint') }}</p>
            @error('thumbnail') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                {{ __('teacher.description') }}
            </label>
            <textarea name="description" rows="5" placeholder="{{ __('teacher.description_placeholder') }}"
                      class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">{{ old('description') }}</textarea>
            @error('description') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
        </div>

        {{-- Learning Outcomes & Requirements --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.learning_outcomes') }}
                </label>
                <textarea name="learning_outcomes" rows="4" placeholder="{{ __('teacher.learning_outcomes_placeholder') }}"
                          class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">{{ old('learning_outcomes') }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.requirements') }}
                </label>
                <textarea name="requirements" rows="4" placeholder="{{ __('teacher.requirements_placeholder') }}"
                          class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">{{ old('requirements') }}</textarea>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <x-button variant="blue" size="md" type="submit" class="gap-2">
                <span>{{ __('teacher.continue_to_curriculum') }} &rarr;</span>
            </x-button>
        </div>
    </form>
</div>
@endsection
