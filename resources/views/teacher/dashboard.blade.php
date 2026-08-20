@extends('teacher.layouts.teacher')

@section('teacher-content')
<div class="space-y-8">

    {{-- Top Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        {{-- Total Courses --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('teacher.total_courses') }}</p>
                <h3 class="text-3xl font-black text-slate-900 mt-2">{{ $totalCourses }}</h3>
                <p class="text-xs text-slate-400 font-semibold mt-1">
                    {{ $publishedCourses }} {{ __('teacher.status_published') }} • {{ $draftCourses }} {{ __('teacher.status_draft') }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
        </div>

        {{-- Total Enrolled Students --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('teacher.total_students') }}</p>
                <h3 class="text-3xl font-black text-slate-900 mt-2">{{ number_format($totalStudents) }}</h3>
                <p class="text-xs text-emerald-600 font-bold mt-1">
                    {{ __('teacher.students') }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" /></svg>
            </div>
        </div>

        {{-- Estimated Earnings --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('teacher.estimated_earnings') }}</p>
                <h3 class="text-2xl font-black text-blue-600 mt-2">{{ number_format($estimatedEarnings) }} <span class="text-sm font-bold text-slate-500">đ</span></h3>
                <p class="text-xs text-slate-400 font-semibold mt-1">{{ __('teacher.from_paid_orders') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        {{-- Pending Courses --}}
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('teacher.pending_courses') }}</p>
                <h3 class="text-3xl font-black text-amber-600 mt-2">{{ $pendingCourses }}</h3>
                <p class="text-xs text-slate-400 font-semibold mt-1">{{ __('teacher.pending_admin_approval') }}</p>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    {{-- Main Grid: Recent Enrollments & Top Courses --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Col 1 & 2: Recent Enrollments --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-black text-slate-900 tracking-tight">{{ __('teacher.recent_enrollments') }}</h3>
                    <p class="text-xs font-medium text-slate-500 mt-0.5">{{ __('teacher.recent_enrollments_desc') }}</p>
                </div>
                <a href="{{ route('teacher.students.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                    {{ __('teacher.view_all') }} &rarr;
                </a>
            </div>

            <div class="mt-4 divide-y divide-slate-100">
                @forelse($recentEnrollments as $enr)
                    <div class="py-3.5 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <x-user-avatar :user="$enr->user" size="md" />
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $enr->user->name }}</p>
                                <p class="text-xs font-medium text-slate-500 truncate">{{ $enr->course->title }}</p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="text-xs font-bold text-slate-400">{{ $enr->enrolled_at ? $enr->enrolled_at->format('d/m/Y H:i') : $enr->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400 text-sm font-medium">
                        {{ __('teacher.no_students_yet') }}
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Col 3: Top Performing Courses & Quick Action --}}
        <div class="space-y-6">

            {{-- Quick Create Course Action Banner --}}
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-md relative overflow-hidden">
                <div class="relative z-10 space-y-3">
                    <span class="px-2.5 py-1 bg-white/20 text-white text-[10px] font-black uppercase tracking-wider rounded-md">{{ __('teacher.create_course') }}</span>
                    <h4 class="text-xl font-black leading-tight">{{ __('teacher.banner_title') }}</h4>
                    <p class="text-xs text-blue-100 font-medium">{{ __('teacher.banner_desc') }}</p>
                    <div class="pt-2">
                        <a href="{{ route('teacher.courses.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-600 rounded-xl text-xs font-black hover:bg-blue-50 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            {{ __('teacher.create_course') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Top Courses List --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-900 tracking-tight">{{ __('teacher.top_performing_courses') }}</h3>
                </div>
                <div class="mt-3 divide-y divide-slate-100">
                    @forelse($topCourses as $course)
                        <div class="py-3 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <a href="{{ route('teacher.courses.edit', $course) }}" class="text-xs font-bold text-slate-800 hover:text-blue-600 truncate block">
                                    {{ $course->title }}
                                </a>
                                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">{{ $course->level }}</span>
                            </div>
                            <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-black rounded-lg shrink-0">
                                {{ __('teacher.students_count_badge', ['count' => $course->enrollments_count]) }}
                            </span>
                        </div>
                    @empty
                        <div class="py-6 text-center text-slate-400 text-xs font-medium">
                            {{ __('teacher.no_courses_yet') }}
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
