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
                    <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="text-orange-500">🎯</span>
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
                    <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span class="text-orange-500">📋</span>
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
                <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="text-orange-500">👨‍🏫</span>
                    <span>{{ __('messages.instructor_title') }}</span>
                </h2>
                <div class="flex items-start gap-4 pt-2">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black flex items-center justify-center text-xl shadow-md flex-shrink-0">
                        {{ strtoupper(substr($course->teacher->name ?? 'G', 0, 1)) }}
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
