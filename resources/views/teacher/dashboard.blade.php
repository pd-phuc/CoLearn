@extends('teacher.layouts.teacher')

@section('teacher-content')
<div class="space-y-8">

    {{-- Top Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <x-stat-card :label="__('teacher.total_courses')" :value="$totalCourses" color="blue" :subtitle="$publishedCourses . ' ' . __('teacher.status_published') . ' • ' . $draftCourses . ' ' . __('teacher.status_draft')">
            <x-slot:icon><x-icon name="book" size="lg" /></x-slot:icon>
        </x-stat-card>

        <x-stat-card :label="__('teacher.total_students')" :value="number_format($totalStudents)" color="emerald" :subtitle="__('teacher.students')">
            <x-slot:icon><x-icon name="users" size="lg" /></x-slot:icon>
        </x-stat-card>

        <x-stat-card :label="__('teacher.estimated_earnings')" :value="number_format($estimatedEarnings)" suffix="đ" color="indigo" value-class="text-blue-600" :subtitle="__('teacher.from_paid_orders')">
            <x-slot:icon><x-icon name="currency" size="lg" /></x-slot:icon>
        </x-stat-card>

        <x-stat-card :label="__('teacher.pending_courses')" :value="$pendingCourses" color="amber" value-class="text-amber-600" :subtitle="__('teacher.pending_admin_approval')">
            <x-slot:icon><x-icon name="clock" size="lg" /></x-slot:icon>
        </x-stat-card>
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
