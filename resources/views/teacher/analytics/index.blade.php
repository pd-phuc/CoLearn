@extends('teacher.layouts.teacher')

@section('teacher-content')
    <div class="space-y-8">
        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('teacher.analytics_title') }}</h1>
            <p class="text-xs font-medium text-slate-500 mt-1">{{ __('teacher.analytics_desc') }}</p>
        </div>

        {{-- Monthly Chart / Grid Summary --}}
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-black text-slate-900">{{ __('teacher.monthly_revenue') }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5">{{ __('teacher.monthly_revenue_desc') }}</p>
                </div>
            </div>

            {{-- Bar Visualizer --}}
            <div class="grid grid-cols-6 sm:grid-cols-12 gap-2 sm:gap-4 items-end h-64 pt-8">
                @php
                    $maxRev = max(max($monthlyRevenue), 1);
                @endphp

                @foreach ($months as $idx => $month)
                    @php
                        $rev = $monthlyRevenue[$idx];
                        $enr = $monthlyEnrollments[$idx];
                        $heightPct = min(max(round(($rev / $maxRev) * 100), 4), 100);
                    @endphp

                    <div class="flex flex-col items-center gap-2 group relative h-full justify-end">
                        {{-- Tooltip --}}
                        <div
                            class="absolute -top-12 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-[10px] py-1 px-2 rounded-lg pointer-events-none whitespace-nowrap z-20 shadow-md"
                        >
                            <p class="font-bold">{{ number_format($rev) }} đ</p>
                            <p class="text-slate-400">{{ $enr }} {{ __('teacher.students_suffix') }}</p>
                        </div>

                        <div
                            class="w-full bg-blue-100 rounded-t-xl overflow-hidden flex flex-col justify-end"
                            style="height: {{ $heightPct }}%"
                        >
                            <div
                                class="w-full bg-gradient-to-t from-blue-600 to-indigo-500 h-full rounded-t-xl transition-all group-hover:from-blue-700 group-hover:to-indigo-600"
                            ></div>
                        </div>
                        <span class="text-[9px] font-bold text-slate-400 text-center truncate w-full">
                            {{ substr($month, 0, 3) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Course Performance Table --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-base font-black text-slate-900">{{ __('teacher.course_performance_detail') }}</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-black text-slate-500 uppercase tracking-wider"
                        >
                            <th class="py-3.5 px-6">{{ __('teacher.th_course') }}</th>
                            <th class="py-3.5 px-4">{{ __('teacher.th_price') }}</th>
                            <th class="py-3.5 px-4">{{ __('teacher.th_students') }}</th>
                            <th class="py-3.5 px-6 text-right">{{ __('teacher.th_revenue') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs">
                        @forelse ($courseStats as $course)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="py-4 px-6 font-extrabold text-slate-900">
                                    {{ $course->title }}
                                </td>
                                <td class="py-4 px-4 font-bold text-slate-600">
                                    {{ number_format($course->discount_price ?? $course->price) }} đ
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-extrabold rounded-lg">
                                        {{ __('teacher.students_count_badge', ['count' => $course->enrollments_count]) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right font-black text-slate-900 text-sm">
                                    {{ number_format($course->total_revenue) }} đ
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 font-medium">
                                    {{ __('teacher.no_courses_yet') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
