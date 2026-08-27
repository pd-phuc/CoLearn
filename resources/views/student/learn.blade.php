<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100 text-slate-900">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ $lesson->title }} — {{ $course->title }} | {{ __('messages.app_name') }}</title>

        <!-- Google Fonts Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
            rel="stylesheet"
        />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js CDN -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            /* Custom Sleek Scrollbars */
            ::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            ::-webkit-scrollbar-track {
                background: transparent;
            }
            ::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 9999px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
        </style>
    </head>
    <body
        class="h-full font-sans antialiased bg-slate-100 text-slate-900 flex flex-col overflow-hidden"
        x-data="{
            sidebarOpen: true,
            isCompleted: {{ $isCurrentCompleted ? 'true' : 'false' }},
            progressPercent: {{ $progressPercent }},
            completedCount: {{ $completedCount }},
            totalCount: {{ $totalLessonsCount }},
            completedIds: {{ json_encode($completedLessonIds) }},
            async toggleComplete(lessonId) {
                try {
                    const res = await fetch(
                        '/learn/lessons/' + lessonId + '/toggle-complete',
                        {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name=csrf-token]',
                                ).content,
                                'Accept': 'application/json',
                            },
                        },
                    )
                    if (res.ok) {
                        const data = await res.json()
                        this.isCompleted = data.completed
                        this.progressPercent = data.progress_percent
                        this.completedCount = data.completed_count
                        if (data.completed) {
                            if (! this.completedIds.includes(lessonId)) {
                                this.completedIds.push(lessonId)
                            }
                        } else {
                            this.completedIds = this.completedIds.filter(
                                (id) => id !== lessonId,
                            )
                        }
                    }
                } catch (e) {
                    console.error(e)
                }
            },
        }"
    >
        <!-- Top Navigation Header (Clean Light Header) -->
        <header class="bg-white border-b border-slate-200 shrink-0 z-30 shadow-xs relative">
            <div class="h-16 px-4 sm:px-6 flex items-center justify-between">
                <!-- Left: Back Button & Course Title -->
                <div class="flex items-center gap-4">
                    <a
                        href="{{ route('courses.show', $course->slug) }}"
                        class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 transition-colors flex items-center justify-center shrink-0 cursor-pointer"
                        title="{{ __('messages.back_to_login') }}"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                    </a>
                    <div class="truncate max-w-md">
                        <h1 class="text-sm font-extrabold text-slate-900 truncate">{{ $course->title }}</h1>
                        <p class="text-xs text-slate-500 font-medium truncate">
                            {{ $course->category->name }} &bull;
                            {{ $course->teacher->name ?? __('messages.default_instructor_name') }}
                        </p>
                    </div>
                </div>

                <!-- Right Toolbar: Progress Badge & Sidebar Toggle Button (☰) -->
                <div class="flex items-center gap-3">
                    <!-- Clean Progress Badge -->
                    <div
                        class="hidden sm:flex items-center gap-2 bg-slate-100 px-3.5 py-1.5 rounded-full border border-slate-200 text-xs font-bold text-slate-700"
                    >
                        <span>{{ __('messages.progress_label') }}:</span>
                        <span class="text-orange-600 font-extrabold">
                            <span x-text="completedCount"></span>
                            /
                            <span x-text="totalCount"></span>
                            (
                            <span x-text="progressPercent + '%'"></span>
                            )
                        </span>
                    </div>

                    <!-- Sidebar Toggle Button (☰ Icon Only) -->
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 hover:text-slate-900 transition-colors flex items-center justify-center border border-slate-200 cursor-pointer"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Sleek Bottom Progress Bar Line -->
            <div class="w-full bg-slate-200 h-1 relative overflow-hidden">
                <div
                    class="bg-gradient-to-r from-orange-500 to-amber-500 h-full transition-all duration-500"
                    :style="'width: ' + progressPercent + '%'"
                ></div>
            </div>
        </header>

        <!-- Main Workspace Container -->
        <div class="flex-1 flex overflow-hidden relative">
            <!-- Left Column: Player Area & Lesson Content -->
            <div class="flex-1 overflow-y-auto flex flex-col justify-between p-4 sm:p-6 space-y-6 bg-slate-100">
                <div class="max-w-5xl mx-auto w-full space-y-6">
                    <!-- Video / Content Player Window (Clean 1px Border) -->
                    <div
                        class="aspect-video w-full bg-black rounded-2xl overflow-hidden border border-slate-200 shadow-xs relative"
                    >
                        @if ($lesson->type === 'video' && $lesson->video_url)
                            @if (str_contains($lesson->video_url, 'youtube.com') || str_contains($lesson->video_url, 'youtu.be'))
                                @php
                                    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $lesson->video_url, $matches);
                                    $youtubeId = $matches[1] ?? null;
                                @endphp

                                @if ($youtubeId)
                                    <iframe
                                        class="w-full h-full border-0"
                                        src="https://www.youtube.com/embed/{{ $youtubeId }}?autoplay=1&rel=0"
                                        allow="
                                            accelerometer;
                                            autoplay;
                                            clipboard-write;
                                            encrypted-media;
                                            gyroscope;
                                            picture-in-picture;
                                        "
                                        allowfullscreen
                                    ></iframe>
                                @else
                                    <video controls class="w-full h-full object-cover">
                                        <source src="{{ $lesson->video_url }}" type="video/mp4" />
                                        {{ __('messages.video_not_supported') }}
                                    </video>
                                @endif
                            @else
                                <video controls autoplay class="w-full h-full object-cover">
                                    <source src="{{ $lesson->video_url }}" type="video/mp4" />
                                    {{ __('messages.video_not_supported') }}
                                </video>
                            @endif
                        @else
                            <!-- Text / Document Lesson Viewer -->
                            <div
                                class="w-full h-full bg-white p-8 flex flex-col justify-center items-center text-center space-y-4"
                            >
                                <div
                                    class="w-16 h-16 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center"
                                >
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-extrabold text-slate-900">{{ $lesson->title }}</h3>
                                <p class="text-sm text-slate-600 max-w-lg leading-relaxed">
                                    {{ $lesson->content ?: __('messages.document_lesson_notice') }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Interactive Action Toolbar Card -->
                    <div
                        class="bg-white border border-slate-200/80 rounded-2xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs"
                    >
                        <div>
                            <h2 class="text-lg font-black text-slate-900">{{ $lesson->title }}</h2>
                            <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold mt-1">
                                <span>{{ $lesson->section->title }}</span>
                                <span>&bull;</span>
                                <span>
                                    {{ sprintf('%02d:%02d', floor($lesson->duration / 60), $lesson->duration % 60) }}
                                    {{ __('messages.minutes_short') }}
                                </span>
                                @if ($lesson->is_free_preview)
                                    <span
                                        class="px-2 py-0.5 rounded-md bg-orange-100 text-orange-700 text-[10px] font-extrabold uppercase tracking-wider"
                                    >
                                        {{ __('messages.free_preview_badge') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <!-- Mark as Completed Toggle Button with Elevated Uncompleted State & Solid Green Completed State -->
                            <button
                                @click="toggleComplete('{{ $lesson->id }}')"
                                :class="isCompleted ? 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-md shadow-emerald-500/20' : 'bg-white hover:bg-slate-50 text-slate-800 border border-slate-300 shadow-sm'"
                                class="px-4 py-2.5 text-xs font-bold rounded-xl flex items-center justify-center gap-2.5 transition-all cursor-pointer flex-1 sm:flex-none"
                            >
                                <svg
                                    class="w-4 h-4 shrink-0"
                                    :class="isCompleted ? 'text-white' : 'text-slate-500'"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2.5"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                                <span
                                    x-text="
                                        isCompleted
                                            ? '{{ __('messages.completed_status') }}'
                                            : '{{ __('messages.mark_as_completed') }}'
                                    "
                                ></span>
                            </button>

                            <!-- Next Lesson Button -->
                            @if ($nextLesson)
                                <a
                                    href="{{ route('learn.show', ['course' => $course->slug, 'lesson' => $nextLesson->id]) }}"
                                    class="btn-primary py-2.5 px-5 text-xs font-bold rounded-xl flex items-center justify-center gap-2 shadow-md shadow-orange-500/20 flex-1 sm:flex-none"
                                >
                                    <span>{{ __('messages.next_lesson') }}</span>
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                                        />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Lesson Content Details & Notes Card -->
                    @if ($lesson->content || $lesson->document_path)
                        <div class="bg-white border border-slate-200/80 rounded-2xl p-6 space-y-4 shadow-xs">
                            <h3 class="text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                                {{ __('messages.lesson_notes_title') }}
                            </h3>
                            @if ($lesson->content)
                                <div class="text-sm text-slate-700 leading-relaxed font-medium">
                                    {!! nl2br(e($lesson->content)) !!}
                                </div>
                            @endif

                            @if ($lesson->document_path)
                                <div class="pt-2">
                                    <a
                                        href="{{ asset($lesson->document_path) }}"
                                        download
                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-orange-600 rounded-xl text-xs font-bold transition-colors border border-slate-200"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                            />
                                        </svg>
                                        <span>{{ __('messages.download_document') }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Footer Copyright Notice -->
                <div class="text-center py-4 text-xs text-slate-500 font-medium">
                    &copy; {{ date('Y') }} {{ __('messages.app_name') }}. {{ __('messages.footer_rights') }}
                </div>
            </div>

            <!-- Right Column: Curriculum Sidebar Accordion -->
            <aside
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-full sm:w-96 bg-white border-l border-slate-200 flex flex-col h-full z-20 shrink-0 shadow-lg"
            >
                <!-- Sidebar Header -->
                <div class="p-4 border-b border-slate-200 flex items-center justify-between bg-white">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900">
                            {{ __('messages.course_curriculum_title') }}
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">
                            <span x-text="completedCount"></span>
                            /
                            <span x-text="totalCount"></span>
                            {{ __('messages.lessons_completed_sub') }}
                        </p>
                    </div>
                    <button @click="sidebarOpen = false" class="text-slate-400 hover:text-slate-700 p-1 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Sections Accordion List -->
                <div class="flex-1 overflow-y-auto divide-y divide-slate-200">
                    @foreach ($course->sections as $sectionIndex => $section)
                        <div x-data="{ open: true }">
                            <!-- Section Accordion Header -->
                            <button
                                @click="open = !open"
                                class="w-full p-4 flex items-center justify-between text-left bg-slate-50 hover:bg-slate-100 transition-colors border-b border-slate-200"
                            >
                                <div>
                                    <h4 class="text-xs font-extrabold text-slate-800 uppercase tracking-wider">
                                        {{ __('messages.section_num', ['num' => $sectionIndex + 1]) }}:
                                        {{ $section->title }}
                                    </h4>
                                    <p class="text-[11px] text-slate-500 font-semibold mt-0.5">
                                        {{ $section->lessons->count() }} {{ __('messages.lessons_label') }}
                                    </p>
                                </div>
                                <svg
                                    class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                    :class="open ? 'rotate-180' : ''"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>

                            <!-- Lessons List Under Section -->
                            <div x-show="open" class="divide-y divide-slate-100 bg-white">
                                @foreach ($section->lessons as $item)
                                    @php
                                        $isActive = $item->id === $lesson->id;
                                        $canAccess = $isEnrolled || $item->is_free_preview;
                                    @endphp

                                    <a
                                        href="{{ $canAccess ? route('learn.show', ['course' => $course->slug, 'lesson' => $item->id]) : '#' }}"
                                        class="p-3.5 flex items-start gap-3 transition-colors {{ $isActive ? 'bg-orange-50/80 border-l-4 border-orange-500 text-orange-950 font-bold' : 'hover:bg-slate-50 text-slate-700' }} {{ ! $canAccess ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    >
                                        <!-- Dynamic Lesson Status Icon -->
                                        <div class="mt-0.5 shrink-0">
                                            <template x-if="completedIds.includes('{{ $item->id }}')">
                                                <div
                                                    class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"
                                                >
                                                    <svg
                                                        class="w-3.5 h-3.5"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="3"
                                                            d="M5 13l4 4L19 7"
                                                        />
                                                    </svg>
                                                </div>
                                            </template>
                                            <template x-if="! completedIds.includes('{{ $item->id }}')">
                                                <div>
                                                    @if ($isActive)
                                                        <div
                                                            class="w-5 h-5 rounded-full bg-orange-500 text-white flex items-center justify-center"
                                                        >
                                                            <svg
                                                                class="w-3 h-3 fill-current ml-0.5"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path d="M8 5v14l11-7z" />
                                                            </svg>
                                                        </div>
                                                    @elseif (! $canAccess)
                                                        <div
                                                            class="w-5 h-5 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center"
                                                        >
                                                            <svg
                                                                class="w-3.5 h-3.5"
                                                                fill="none"
                                                                viewBox="0 0 24 24"
                                                                stroke="currentColor"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                                                />
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="w-5 h-5 rounded-full border border-slate-300 text-slate-400 flex items-center justify-center"
                                                        >
                                                            <svg
                                                                class="w-3 h-3 fill-current ml-0.5"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path d="M8 5v14l11-7z" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Lesson Meta -->
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-xs font-semibold truncate leading-tight {{ $isActive ? 'text-orange-600 font-extrabold' : 'text-slate-800' }}"
                                            >
                                                {{ $item->title }}
                                            </p>
                                            <div
                                                class="flex items-center gap-2 text-[10px] text-slate-500 font-medium mt-1"
                                            >
                                                <span>
                                                    {{ sprintf('%02d:%02d', floor($item->duration / 60), $item->duration % 60) }}
                                                    {{ __('messages.minutes_short') }}
                                                </span>
                                                @if ($item->is_free_preview && ! $isEnrolled)
                                                    <span class="text-orange-600 font-bold">
                                                        {{ __('messages.free_preview_badge') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </aside>
        </div>
    </body>
</html>
