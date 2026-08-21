@extends('teacher.layouts.teacher')

@section('teacher-content')
<div class="space-y-6">

    {{-- Top Header Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ __('teacher.my_courses') }}</h1>
            <p class="text-xs font-medium text-slate-500 mt-1">{{ __('teacher.my_courses_desc') }}</p>
        </div>
        <x-button variant="blue" size="md" href="{{ route('teacher.courses.create') }}" class="gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            {{ __('teacher.create_course') }}
        </x-button>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-col md:flex-row gap-4">
        <form method="GET" action="{{ route('teacher.courses.index') }}" class="flex-1 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('teacher.search_course_placeholder') }}" class="w-full pl-10 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-medium">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </div>

            <select name="status" onchange="this.form.submit()" class="px-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-blue-500 focus:outline-none font-bold text-slate-700">
                <option value="">{{ __('teacher.all_statuses') }}</option>
                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>{{ __('teacher.status_draft') }}</option>
                <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>{{ __('teacher.status_pending_review') }}</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>{{ __('teacher.status_published') }}</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>{{ __('teacher.status_archived') }}</option>
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors">
                {{ __('teacher.filter') }}
            </button>
        </form>
    </div>

    {{-- Course List Table --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">{{ __('teacher.th_course') }}</th>
                        <th class="py-3.5 px-4">{{ __('teacher.th_category') }}</th>
                        <th class="py-3.5 px-4">{{ __('teacher.th_price') }}</th>
                        <th class="py-3.5 px-4">{{ __('teacher.th_curriculum') }}</th>
                        <th class="py-3.5 px-4">{{ __('teacher.th_students') }}</th>
                        <th class="py-3.5 px-4">{{ __('teacher.th_status') }}</th>
                        <th class="py-3.5 px-6 text-right">{{ __('teacher.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3.5 min-w-[240px]">
                                    @if($course->thumbnail)
                                        <img src="{{ $course->thumbnail }}" alt="" class="w-14 h-10 object-cover rounded-lg border border-slate-200 shrink-0">
                                    @else
                                        <div class="w-14 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 font-bold shrink-0 text-[10px]">
                                            {{ __('teacher.no_image') }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <a href="{{ route('teacher.courses.edit', $course) }}" class="font-extrabold text-slate-900 hover:text-blue-600 block truncate">
                                            {{ $course->title }}
                                        </a>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $course->level }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-600 whitespace-nowrap">
                                {{ $course->category->name ?? '—' }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                @if($course->price == 0)
                                    <span class="font-black text-emerald-600 uppercase">{{ __('teacher.free') }}</span>
                                @else
                                    <span class="font-black text-slate-900">{{ number_format($course->discount_price ?? $course->price) }} đ</span>
                                    @if($course->discount_price)
                                        <span class="text-[10px] text-slate-400 line-through block">{{ number_format($course->price) }} đ</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-700 whitespace-nowrap">
                                {{ $course->sections_count }} {{ __('teacher.chapters') }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-extrabold rounded-lg">
                                    {{ __('teacher.students_count_badge', ['count' => $course->enrollments_count]) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <x-course-status-badge :status="$course->status" />
                            </td>
                            <td class="py-4 px-6 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <x-button variant="secondary" size="xs" href="{{ route('teacher.courses.edit', $course) }}">
                                        {{ __('teacher.edit_course') }}
                                    </x-button>

                                    @if($course->status === 'draft')
                                        <form action="{{ route('teacher.courses.submit-review', $course) }}" method="POST" onsubmit="return confirm('{{ __('teacher.submit_for_review_confirm') }}')">
                                            @csrf
                                            <x-button variant="blue" size="xs" type="submit">
                                                {{ __('teacher.submit_for_review') }}
                                            </x-button>
                                        </form>

                                        <form action="{{ route('teacher.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('{{ __('teacher.delete_draft_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 font-medium">
                                {{ __('teacher.no_courses_yet') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($courses->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $courses->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
