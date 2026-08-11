@extends('admin.layouts.admin')

@section('admin-content')
<div class="space-y-6">
    {{-- Search & Filter Header --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl p-4 shadow-xs">
        <form class="flex flex-wrap items-center justify-between gap-4" method="GET">
            <div class="flex flex-wrap items-center gap-3 flex-1 min-w-[280px]">
                <div class="relative flex-1 min-w-[200px]">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="{{ __('admin.search_courses_placeholder') }}"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 bg-slate-50/80 border border-slate-200/80 rounded-xl text-sm font-bold text-slate-700 focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all">
                    <option value="">{{ __('admin.all_statuses') }}</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="pending_review" {{ request('status') === 'pending_review' ? 'selected' : '' }}>Pending Review</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
                <button type="submit" class="btn-primary px-5 py-2.5 rounded-xl text-xs font-extrabold uppercase shadow-sm">{{ __('admin.filter') }}</button>
            </div>
            <span class="text-xs font-bold text-slate-500">{{ __('messages.showing_courses_count', ['total' => $courses->count()]) }}</span>
        </form>
    </div>

    {{-- Courses Data Table --}}
    <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50/80 border-b border-slate-200/80">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('admin.course_info') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.instructor') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.order_status') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider">{{ __('messages.price') }}</th>
                        <th class="px-6 py-3.5 text-xs font-extrabold text-slate-500 uppercase tracking-wider text-right">{{ __('admin.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="px-6 py-4">
                                <div>
                                    <span class="px-2 py-0.5 bg-orange-50 text-orange-700 text-[10px] font-extrabold uppercase tracking-wider rounded border border-orange-200/60 mb-1 inline-block">
                                        {{ $course->category?->name ?? 'General' }}
                                    </span>
                                    <p class="text-sm font-extrabold text-slate-900 line-clamp-1">{{ $course->title }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs font-bold text-slate-700">
                                {{ $course->teacher?->name ?? 'N/A' }}
                                <p class="text-[10px] text-slate-400 font-normal">{{ $course->teacher?->email }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @php $sc = ['published' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60', 'draft' => 'bg-slate-100 text-slate-500 border-slate-200', 'pending_review' => 'bg-amber-50 text-amber-700 border-amber-200/60', 'archived' => 'bg-slate-100 text-slate-400']; @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider border {{ $sc[$course->status] ?? 'bg-slate-100 text-slate-500' }}">
                                    {{ str_replace('_', ' ', $course->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-black text-slate-900">
                                {{ number_format($course->price, 0, ',', '.') }} đ
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.courses.show', $course) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-orange-50 hover:text-orange-600 text-slate-700 rounded-xl text-xs font-bold transition-colors">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ __('admin.review_course') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-xs font-bold text-slate-400">
                                {{ __('admin.no_courses_matching') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $courses->links() }}</div>
</div>
@endsection
