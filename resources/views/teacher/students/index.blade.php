@extends('teacher.layouts.teacher')

@section('teacher-content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('teacher.students_title') }}</h1>
        <p class="text-xs font-medium text-slate-500 mt-1">{{ __('teacher.students_desc') }}</p>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-col md:flex-row gap-4">
        <form method="GET" action="{{ route('teacher.students.index') }}" class="flex-1 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('teacher.search_student_placeholder') }}" class="w-full pl-10 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <select name="course_id" onchange="this.form.submit()" class="px-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-bold text-slate-700">
                <option value="">{{ __('teacher.all_courses') }}</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors">
                {{ __('teacher.filter') }}
            </button>
        </form>
    </div>

    {{-- Students Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">{{ __('teacher.student_name') }}</th>
                        <th class="py-3.5 px-4">{{ __('teacher.enrolled_course') }}</th>
                        <th class="py-3.5 px-4">{{ __('teacher.enrolled_at') }}</th>
                        <th class="py-3.5 px-6">{{ __('teacher.progress') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($enrollments as $enr)
                        @php
                            $totalLessons = $enr->course->sections->flatMap->lessons->count();
                            $completedLessons = \App\Models\LessonCompletion::where('user_id', $enr->user_id)
                                ->whereIn('lesson_id', $enr->course->sections->flatMap->lessons->pluck('id'))
                                ->count();
                            $progressPct = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if($enr->user->avatar)
                                        <img src="{{ $enr->user->avatar }}" alt="" class="w-9 h-9 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                            {{ strtoupper(substr($enr->user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $enr->user->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-medium">{{ $enr->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-800">
                                {{ $enr->course->title }}
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-500 whitespace-nowrap">
                                {{ $enr->enrolled_at ? $enr->enrolled_at->format('d/m/Y H:i') : $enr->created_at->format('d/m/Y') }}
                            </td>
                            <td class="py-4 px-6 min-w-[200px]">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 bg-slate-100 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $progressPct }}%"></div>
                                    </div>
                                    <span class="text-xs font-black text-slate-700 w-12 text-right">{{ $progressPct }}%</span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-semibold mt-0.5 block">
                                    {{ __('teacher.lessons_completed_of', ['completed' => $completedLessons, 'total' => $totalLessons]) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400 font-medium">
                                {{ __('teacher.no_students_yet') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($enrollments->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
