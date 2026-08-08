@extends('layouts.app')

@section('content')
<div class="mb-16">

    <!-- Hero Banner Component -->
    @include('courses.partials.hero-banner')

    <!-- Main Grid: Left Details vs Right Sticky Card -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Content Column -->
        <main class="lg:col-span-8 space-y-10">

            <!-- What You'll Learn Box (card-fcode) -->
            @if(is_array($course->learning_outcomes) && count($course->learning_outcomes) > 0)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs space-y-4">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ __('messages.what_you_will_learn') }}</span>
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm font-semibold text-slate-700 pt-2">
                        @foreach($course->learning_outcomes as $outcome)
                            <div class="flex items-start gap-2.5">
                                <span class="text-emerald-500 font-bold mt-0.5">✓</span>
                                <span>{{ $outcome }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Requirements Box -->
            @if(is_array($course->requirements) && count($course->requirements) > 0)
                <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs space-y-3">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                        <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>{{ __('messages.course_requirements') }}</span>
                    </h2>
                    <ul class="space-y-2 text-sm font-medium text-slate-700 pt-1">
                        @foreach($course->requirements as $req)
                            <div class="flex items-center gap-2">
                                <span class="text-orange-500 font-bold">&bull;</span>
                                <span>{{ $req }}</span>
                            </div>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Curriculum Accordion Component -->
            @include('courses.partials.curriculum-accordion')

            <!-- Instructor Profile Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs space-y-4">
                <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                    <svg class="w-5 h-5 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>{{ __('messages.instructor_title') }}</span>
                </h2>
                <div class="flex items-start gap-4 pt-2">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black flex items-center justify-center text-xl shadow-md flex-shrink-0 overflow-hidden ring-2 ring-orange-500/20">
                        @if($course->teacher && $course->teacher->avatar)
                            <img src="{{ $course->teacher->avatar }}" alt="{{ $course->teacher->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($course->teacher->name ?? 'G', 0, 1)) }}
                        @endif
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-extrabold text-slate-900">{{ $course->teacher->name ?? 'Giảng Viên CoLearn' }}</h3>
                        <p class="text-xs font-bold text-orange-600">{{ __('messages.instructor_subtitle') }}</p>
                        <p class="text-xs text-slate-500 leading-relaxed pt-1">
                            {{ __('messages.instructor_bio') }}
                        </p>
                    </div>
                </div>
            </div>

        </main>

        <!-- Right Sticky Purchase Card Column -->
        <aside class="lg:col-span-4">
            @include('courses.partials.sticky-purchase-card')
        </aside>

    </div>

</div>

<!-- Free Preview Video Modal Component -->
@include('courses.partials.preview-modal')

@endsection
