@extends('teacher.layouts.teacher')

@section('teacher-content')
<div class="space-y-6" x-data="{ activeTab: 'curriculum' }">

    {{-- Top Navigation & Action Banner --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs">
        <div>
            <a href="{{ route('teacher.courses.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-blue-600 mb-2">
                <x-icon name="arrow-left" size="sm" />
                {{ __('teacher.back_to_courses') }}
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $course->title }}</h1>
                <x-course-status-badge :status="$course->status" />
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if($course->status === 'published')
                <a href="{{ route('courses.show', $course->slug) }}" target="_blank" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                    {{ __('teacher.view_course_page') }} &nearr;
                </a>
            @endif

            @if($course->status === 'draft')
                <form action="{{ route('teacher.courses.submit-review', $course) }}" method="POST" onsubmit="return confirm('{{ __('teacher.submit_for_review_confirm') }}')">
                    @csrf
                    <x-button variant="blue" size="md" type="submit" class="gap-2">
                        <x-icon name="check-circle" size="sm" />
                        {{ __('teacher.submit_for_review') }}
                    </x-button>
                </form>
            @endif
        </div>
    </div>

    {{-- Rejection Feedback Notice --}}
    @if($course->rejection_reason && $course->status === 'draft')
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-4 text-rose-800 flex items-start gap-3">
            <x-icon name="warning" size="md" class="text-rose-500 shrink-0 mt-0.5" />
            <div>
                <p class="text-xs font-black uppercase tracking-wider text-rose-900">{{ __('teacher.rejection_reason_notice') }}</p>
                <p class="text-xs font-medium text-rose-700 mt-1">{{ $course->rejection_reason }}</p>
            </div>
        </div>
    @endif

    {{-- Main Tabs Switcher --}}
    <div class="flex items-center gap-2 border-b border-slate-200">
        <button type="button" @click="activeTab = 'curriculum'"
                :class="activeTab === 'curriculum' ? 'border-blue-600 text-blue-600 bg-white font-black shadow-xs' : 'border-transparent text-slate-500 hover:text-slate-900 font-bold'"
                class="px-5 py-3 text-xs border-b-2 rounded-t-xl transition-all flex items-center gap-2">
            <x-icon name="menu" size="sm" />
            {{ __('teacher.curriculum') }} ({{ $course->sections->count() }} {{ __('teacher.chapters') }})
        </button>

        <button type="button" @click="activeTab = 'basic'"
                :class="activeTab === 'basic' ? 'border-blue-600 text-blue-600 bg-white font-black shadow-xs' : 'border-transparent text-slate-500 hover:text-slate-900 font-bold'"
                class="px-5 py-3 text-xs border-b-2 rounded-t-xl transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            {{ __('teacher.basic_info_tab') }}
        </button>
    </div>

    {{-- TAB 1: CURRICULUM BUILDER --}}
    <div x-show="activeTab === 'curriculum'" class="space-y-6">

        {{-- Add Section Card --}}
        <div class="bg-blue-50/60 border border-blue-100 rounded-3xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-sm font-black text-blue-950">{{ __('teacher.add_section') }}</h3>
                <p class="text-xs text-blue-700 font-medium mt-0.5">{{ __('teacher.curriculum_desc') }}</p>
            </div>
            <form action="{{ route('teacher.courses.sections.store', $course) }}" method="POST" class="flex items-center gap-2 w-full sm:w-auto">
                @csrf
                <input type="text" name="title" required placeholder="{{ __('teacher.section_title') }}"
                       class="px-4 py-2.5 text-xs bg-white border border-blue-200 rounded-xl focus:outline-none focus:border-blue-600 font-medium w-full sm:w-80">
                <x-button variant="blue" size="sm" type="submit">
                    {{ __('teacher.save_section') }}
                </x-button>
            </form>
        </div>

        {{-- Sections & Lessons List --}}
        <div class="space-y-4">
            @forelse($course->sections as $index => $section)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden" x-data="{ expanded: true, addLessonOpen: false, editSectionOpen: false }">
                    
                    {{-- Section Header --}}
                    <div class="p-4 sm:p-5 bg-slate-50/80 border-b border-slate-200/60 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0 cursor-pointer" @click="expanded = !expanded">
                            <button type="button" class="text-slate-400 hover:text-slate-600 transition-transform" :class="{ 'rotate-180': expanded }">
                                <x-icon name="chevron-down" size="sm" />
                            </button>
                            <div class="min-w-0">
                                <h4 class="text-sm font-black text-slate-900 truncate">
                                    {{ __('teacher.chapter_label') }} {{ $index + 1 }}: {{ $section->title }}
                                </h4>
                                <span class="text-[11px] font-semibold text-slate-500">{{ $section->lessons->count() }} {{ __('teacher.lessons_count') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            <x-button variant="blue" size="xs" type="button" @click="addLessonOpen = !addLessonOpen" class="gap-1">
                                <x-icon name="plus" size="xs" />
                                {{ __('teacher.add_lesson') }}
                            </x-button>

                            <button type="button" @click="editSectionOpen = !editSectionOpen" class="p-1.5 text-slate-400 hover:text-slate-700 transition-colors">
                                <x-icon name="pencil" size="sm" />
                            </button>

                            <form action="{{ route('teacher.sections.destroy', $section) }}" method="POST" onsubmit="return confirm('{{ __('teacher.delete_section_confirm') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors">
                                    <x-icon name="trash" size="sm" />
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Edit Section Inline Modal --}}
                    <div x-show="editSectionOpen" class="p-4 bg-amber-50/60 border-b border-amber-100 flex items-center gap-3">
                        <form action="{{ route('teacher.sections.update', $section) }}" method="POST" class="flex-1 flex items-center gap-2">
                            @csrf
                            @method('PUT')
                            <input type="text" name="title" value="{{ $section->title }}" required class="px-3 py-1.5 text-xs bg-white border border-amber-200 rounded-lg focus:outline-none focus:border-amber-500 font-medium flex-1">
                            <x-button variant="primary" size="xs" type="submit" class="bg-amber-600 hover:bg-amber-700">{{ __('teacher.save') }}</x-button>
                            <x-button variant="secondary" size="xs" type="button" @click="editSectionOpen = false">{{ __('teacher.cancel') }}</x-button>
                        </form>
                    </div>

                    {{-- Add Lesson Form Accordion --}}
                    <div x-show="addLessonOpen" x-transition class="p-5 bg-blue-50/30 border-b border-blue-100">
                        <form action="{{ route('teacher.sections.lessons.store', $section) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <h5 class="text-xs font-black text-blue-900 uppercase tracking-wider">{{ __('teacher.add_lesson') }}</h5>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">{{ __('teacher.lesson_title') }} *</label>
                                    <input type="text" name="title" required placeholder="{{ __('teacher.lesson_title_placeholder') }}" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 font-medium">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">{{ __('teacher.lesson_type') }} *</label>
                                    <select name="type" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 font-bold text-slate-700">
                                        <option value="video">{{ __('teacher.type_video') }}</option>
                                        <option value="text">{{ __('teacher.type_text') }}</option>
                                        <option value="document">{{ __('teacher.type_document') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">{{ __('teacher.video_url') }}</label>
                                    <input type="text" name="video_url" placeholder="{{ __('teacher.video_url_placeholder') }}" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 font-medium">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">{{ __('teacher.duration_minutes') }}</label>
                                    <input type="number" name="duration" min="0" value="10" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 font-bold">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold text-slate-600 mb-1">{{ __('teacher.lesson_content') }}</label>
                                <textarea name="content" rows="3" placeholder="{{ __('teacher.lesson_content_placeholder') }}" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-blue-500 font-medium"></textarea>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_free_preview" value="1" class="w-4 h-4 text-blue-600 rounded border-slate-300">
                                    <span class="text-xs font-bold text-slate-700">{{ __('teacher.is_free_preview') }}</span>
                                </label>
                                <div class="flex gap-2">
                                    <x-button variant="secondary" size="xs" type="button" @click="addLessonOpen = false">{{ __('teacher.cancel') }}</x-button>
                                    <x-button variant="blue" size="xs" type="submit">{{ __('teacher.save_lesson') }}</x-button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Lessons List inside Section --}}
                    <div x-show="expanded" class="divide-y divide-slate-100">
                        @forelse($section->lessons as $lIndex => $lesson)
                            <div class="p-3.5 sm:px-6 flex items-center justify-between gap-3 hover:bg-slate-50/50 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-xs font-bold shrink-0">
                                        @if($lesson->type === 'video')
                                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @elseif($lesson->type === 'text')
                                            <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                        @else
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate">{{ $lesson->title }}</p>
                                        <div class="flex items-center gap-2 text-[10px] text-slate-400 font-semibold mt-0.5">
                                            <span>{{ $lesson->duration }} {{ __('teacher.minutes') }}</span>
                                            @if($lesson->is_free_preview)
                                                <span class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 rounded font-bold">{{ __('teacher.free_preview_badge') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <form action="{{ route('teacher.lessons.destroy', $lesson) }}" method="POST" onsubmit="return confirm('{{ __('teacher.delete_lesson_confirm') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-6 text-center text-slate-400 text-xs font-medium">
                                {{ __('teacher.no_lessons_in_section') }}
                            </div>
                        @endforelse
                    </div>

                </div>
            @empty
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200/80 shadow-xs">
                    <p class="text-sm font-bold text-slate-500">{{ __('teacher.no_sections_in_course') }}</p>
                    <p class="text-xs text-slate-400 mt-1">{{ __('teacher.no_sections_hint') }}</p>
                </div>
            @endforelse
        </div>

    </div>

    {{-- TAB 2: BASIC INFO & PRICING --}}
    <div x-show="activeTab === 'basic'" class="space-y-6">
        <form action="{{ route('teacher.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            @csrf
            @method('PUT')

            {{-- Course Title --}}
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.course_title') }} <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" required
                       class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                @error('title') <p class="text-xs text-rose-600 font-bold mt-1.5">{{ $message }}</p> @enderror
            </div>

            {{-- Category & Level Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        {{ __('teacher.category') }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="category_id" required class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-semibold text-slate-700">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        {{ __('teacher.level') }} <span class="text-rose-500">*</span>
                    </label>
                    <select name="level" required class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-semibold text-slate-700">
                        <option value="all" {{ old('level', $course->level) == 'all' ? 'selected' : '' }}>{{ __('teacher.level_all') }}</option>
                        <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>{{ __('teacher.level_beginner') }}</option>
                        <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>{{ __('teacher.level_intermediate') }}</option>
                        <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>{{ __('teacher.level_advanced') }}</option>
                    </select>
                </div>
            </div>

            {{-- Pricing Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        {{ __('teacher.price') }} <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" name="price" value="{{ old('price', $course->price) }}" min="0" step="1000" required
                           class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-bold">
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        {{ __('teacher.discount_price') }}
                    </label>
                    <input type="number" name="discount_price" value="{{ old('discount_price', $course->discount_price) }}" min="0" step="1000"
                           class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-bold text-blue-600">
                </div>
            </div>

            {{-- Thumbnail Upload --}}
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.thumbnail') }}
                </label>
                @if($course->thumbnail)
                    <div class="mb-3">
                        <img src="{{ $course->thumbnail }}" alt="" class="w-48 h-28 object-cover rounded-xl border border-slate-200">
                    </div>
                @endif
                <input type="file" name="thumbnail" accept="image/*"
                       class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                    {{ __('teacher.description') }}
                </label>
                <textarea name="description" rows="5"
                          class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">{{ old('description', $course->description) }}</textarea>
            </div>

            {{-- Learning Outcomes & Requirements --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        {{ __('teacher.learning_outcomes') }}
                    </label>
                    <textarea name="learning_outcomes" rows="4"
                              class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">{{ old('learning_outcomes', is_array($course->learning_outcomes) ? implode("\n", $course->learning_outcomes) : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider mb-2">
                        {{ __('teacher.requirements') }}
                    </label>
                    <textarea name="requirements" rows="4"
                              class="w-full px-4 py-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">{{ old('requirements', is_array($course->requirements) ? implode("\n", $course->requirements) : '') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-black transition-all">
                    {{ __('teacher.save_basic_info') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
