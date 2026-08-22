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
                <x-icon name="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" />
            </div>

            <select name="course_id" onchange="this.form.submit()" class="px-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-bold text-slate-700">
                <option value="">{{ __('teacher.all_courses') }}</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>{{ $course->title }}</option>
                @endforeach
            </select>

            <x-button type="submit" variant="secondary" size="sm">
                {{ __('teacher.filter') }}
            </x-button>
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
                            $key = $enr->user_id . '-' . $enr->course_id;
                            $progress = $progressMap[$key] ?? ['total' => 0, 'completed' => 0, 'percent' => 0];
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <x-user-avatar :user="$enr->user" size="sm" />
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
                                        <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $progress['percent'] }}%"></div>
                                    </div>
                                    <span class="text-xs font-black text-slate-700 w-12 text-right">{{ $progress['percent'] }}%</span>
                                </div>
                                <span class="text-[10px] text-slate-400 font-semibold mt-0.5 block">
                                    {{ __('teacher.lessons_completed_of', ['completed' => $progress['completed'], 'total' => $progress['total']]) }}
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
