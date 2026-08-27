@extends('layouts.app')

@section('title', __('messages.my_courses_title') . ' - CoLearn')

@section('content')
    <div class="bg-slate-50 min-h-screen py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Title -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ __('messages.my_courses_title') }}
                </h1>
                <p class="text-sm font-semibold text-slate-500 mt-1">{{ __('messages.my_courses_sub') }}</p>
            </div>

            @if ($enrolledCourses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach ($enrolledCourses as $course)
                        <div
                            class="bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group"
                        >
                            <!-- Course Thumbnail Container -->
                            <div class="relative aspect-video overflow-hidden bg-slate-900">
                                @if ($course->thumbnail)
                                    <img
                                        src="{{ $course->thumbnail }}"
                                        alt="{{ $course->title }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    />
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-orange-500 via-amber-500 to-yellow-600 flex items-center justify-center p-6 text-white text-center"
                                    >
                                        <span class="font-black text-lg line-clamp-2">{{ $course->title }}</span>
                                    </div>
                                @endif

                                <div class="absolute top-3 left-3">
                                    <span
                                        class="px-3 py-1 bg-slate-900/80 backdrop-blur-md text-white font-extrabold text-[10px] rounded-full uppercase tracking-wider"
                                    >
                                        {{ $course->category->name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Body -->
                            <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                                <div>
                                    <h3
                                        class="text-base font-extrabold text-slate-900 group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug mb-2"
                                    >
                                        {{ $course->title }}
                                    </h3>

                                    <!-- Instructor Info -->
                                    <div class="flex items-center gap-2 mb-4">
                                        @if ($course->teacher && $course->teacher->avatar)
                                            <img
                                                src="{{ $course->teacher->avatar }}"
                                                alt="{{ $course->teacher->name }}"
                                                class="w-6 h-6 rounded-full object-cover ring-2 ring-orange-500/20"
                                            />
                                        @else
                                            <div
                                                class="w-6 h-6 rounded-full bg-orange-500 text-white font-bold flex items-center justify-center text-[10px]"
                                            >
                                                {{ strtoupper(substr($course->teacher->name ?? 'C', 0, 1)) }}
                                            </div>
                                        @endif
                                        <span class="text-xs font-semibold text-slate-600">
                                            {{ $course->teacher->name ?? 'CoLearn Educator' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Learning Progress Bar -->
                                <div class="space-y-2 border-t border-slate-100 pt-4">
                                    <div class="flex items-center justify-between text-xs font-bold">
                                        <span class="text-slate-600">{{ __('messages.learning_progress') }}</span>
                                        <span class="text-orange-600">{{ $course->progress_percent }}%</span>
                                    </div>

                                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                        <div
                                            class="bg-gradient-to-r from-orange-500 to-amber-500 h-2.5 rounded-full transition-all duration-500"
                                            style="width: {{ $course->progress_percent }}%"
                                        ></div>
                                    </div>

                                    <p class="text-[11px] text-slate-400 font-medium">
                                        {{ __('messages.lessons_completed_count', ['completed' => $course->completed_lessons_count, 'total' => $course->total_lessons_count]) }}
                                    </p>
                                </div>

                                <!-- Action Button -->
                                <a
                                    href="{{ route('courses.show', $course->slug) }}"
                                    class="w-full py-3 bg-orange-50 hover:bg-orange-500 text-orange-600 hover:text-white font-extrabold text-xs rounded-xl transition-all duration-200 flex items-center justify-center gap-2 group/btn"
                                >
                                    <span>{{ __('messages.continue_learning') }}</span>
                                    <svg
                                        class="w-4 h-4 transition-transform group-hover/btn:translate-x-1"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                                        />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div
                    class="bg-white rounded-3xl border border-slate-200/80 p-12 text-center max-w-lg mx-auto my-12 space-y-4 shadow-sm"
                >
                    <div
                        class="w-16 h-16 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center mx-auto"
                    >
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                            />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900">{{ __('messages.no_enrolled_courses') }}</h3>
                    <p class="text-sm text-slate-500">{{ __('messages.no_enrolled_courses_sub') }}</p>
                    <div class="pt-2">
                        <a
                            href="{{ route('courses.index') }}"
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-500 text-white font-extrabold text-xs rounded-xl shadow-lg shadow-orange-500/25 hover:from-orange-600 hover:to-amber-600 transition-all"
                        >
                            {{ __('messages.explore_courses_now') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
