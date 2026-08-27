@extends('admin.layouts.admin')
@section('page-title', 'Course Review: ' . $course->title)
@section('page-description', 'Review course details, syllabus, and publish/reject status')

@section('admin-content')
    <div class="space-y-6">
        <a
            href="{{ route('admin.courses.index') }}"
            class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-orange-600 transition-colors"
        >
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Courses
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            {{-- Left Column: Main Course Information & Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Overview Card --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-4">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <span
                                class="px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-orange-50 text-orange-700 border border-orange-200/60 mb-2 inline-block"
                            >
                                {{ $course->category?->name ?? 'General' }}
                            </span>
                            <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $course->title }}</h2>
                        </div>
                    </div>

                    @if ($course->description)
                        <div class="pt-2 border-t border-slate-100">
                            <h4 class="text-xs font-extrabold uppercase text-slate-400 tracking-wider mb-1">
                                Course Description
                            </h4>
                            <p class="text-sm text-slate-600 leading-relaxed font-medium">
                                {{ $course->description }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Course Content / Syllabus --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">
                            Syllabus Structure
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-xs font-bold">
                            {{ $course->sections->count() }} Sections
                        </span>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($course->sections as $section)
                            <div class="p-6 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-extrabold text-slate-900">{{ $section->title }}</h4>
                                    <span class="text-xs text-slate-400 font-semibold">
                                        {{ $section->lessons->count() }} lessons
                                    </span>
                                </div>
                                <div class="pl-3 border-l-2 border-orange-500/30 space-y-2">
                                    @foreach ($section->lessons as $lesson)
                                        <div
                                            class="flex items-center justify-between py-1 text-xs font-medium text-slate-600"
                                        >
                                            <span class="flex items-center gap-2">
                                                <svg
                                                    class="w-3.5 h-3.5 text-slate-400 shrink-0"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"
                                                    />
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                {{ $lesson->title }}
                                            </span>
                                            <span
                                                class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-bold rounded uppercase"
                                            >
                                                {{ $lesson->type }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-xs text-slate-400 font-bold">
                                No sections or lessons created yet
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Right Column: Course Metadata & Administrative Controls --}}
            <div class="space-y-6">
                {{-- Status & Actions Card --}}
                <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-xs space-y-5">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 block mb-1">
                            Current Status
                        </span>
                        @php
                            $sc = [
                                'published' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                                'pending_review' => 'bg-amber-100 text-amber-700 border-amber-200',
                            ];
                        @endphp

                        <span
                            class="inline-block px-3 py-1 rounded-lg text-xs font-black uppercase tracking-wider border {{ $sc[$course->status] ?? 'bg-slate-100 text-slate-500' }}"
                        >
                            {{ str_replace('_', ' ', $course->status) }}
                        </span>
                    </div>

                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 block mb-0.5">
                                Instructor
                            </span>
                            <p class="text-sm font-bold text-slate-800">
                                {{ $course->teacher?->name ?? 'Unknown Teacher' }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $course->teacher?->email }}</p>
                        </div>

                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 block mb-0.5">
                                Price
                            </span>
                            <p class="text-lg font-black text-slate-900">
                                {{ number_format($course->price, 0, ',', '.') }} đ
                            </p>
                        </div>

                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-slate-400 block mb-0.5">
                                Created Date
                            </span>
                            <p class="text-xs font-semibold text-slate-600">
                                {{ $course->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if ($course->status === 'pending_review')
                        <div class="pt-5 border-t border-slate-100 space-y-3">
                            <form action="{{ route('admin.courses.approve', $course) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase shadow-sm cursor-pointer transition-colors"
                                >
                                    Approve & Publish
                                </button>
                            </form>

                            <div x-data="{ showReason: false }">
                                <button
                                    type="button"
                                    @click="showReason = !showReason"
                                    class="w-full py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 rounded-xl text-xs font-black uppercase cursor-pointer transition-colors"
                                >
                                    Reject Course
                                </button>

                                <form
                                    x-show="showReason"
                                    x-transition
                                    action="{{ route('admin.courses.reject', $course) }}"
                                    method="POST"
                                    class="mt-3 space-y-2"
                                    style="display: none"
                                >
                                    @csrf
                                    <textarea
                                        name="rejection_reason"
                                        required
                                        rows="3"
                                        placeholder="Provide rejection feedback for the instructor..."
                                        class="w-full p-3 text-xs border border-slate-200 rounded-xl focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500 outline-none"
                                    ></textarea>
                                    <button
                                        type="submit"
                                        class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-extrabold cursor-pointer"
                                    >
                                        Confirm Rejection
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
