<!-- Udemy Style Curriculum Accordion -->
<div x-data="{
    openSections: [0],
    toggleSection(index) {
        if (this.openSections.includes(index)) {
            this.openSections = this.openSections.filter(i => i !== index);
        } else {
            this.openSections.push(index);
        }
    },
    expandAll() {
        this.openSections = [{{ implode(',', range(0, max(0, $course->sections->count() - 1))) }}];
    },
    collapseAll() {
        this.openSections = [];
    }
}" class="space-y-6">

    <!-- Header Stats & Expand All Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200/80 pb-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">{{ __('messages.course_content') }}</h2>
            <p class="text-xs font-semibold text-slate-500 mt-1">
                {{ __('messages.curriculum_summary', ['sections' => $course->sections->count(), 'lessons' => $totalLessonsCount, 'duration' => $formattedDuration]) }}
            </p>
        </div>

        <div class="flex items-center gap-2 text-xs font-bold">
            <button @click="expandAll()" class="text-orange-600 hover:text-orange-700 hover:underline cursor-pointer">
                {{ __('messages.expand_all') }}
            </button>
            <span class="text-slate-300">|</span>
            <button @click="collapseAll()" class="text-slate-500 hover:text-slate-700 hover:underline cursor-pointer">
                {{ __('messages.collapse_all') }}
            </button>
        </div>
    </div>

    <!-- Sections List -->
    <div class="space-y-4">
        @forelse($course->sections as $sIndex => $section)
            <div class="border border-slate-200/80 rounded-2xl bg-white overflow-hidden shadow-2xs">

                <!-- Section Header Bar -->
                <button @click="toggleSection({{ $sIndex }})"
                        class="w-full flex items-center justify-between px-6 py-4 bg-slate-50/80 hover:bg-slate-100/80 text-left transition-colors cursor-pointer">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': openSections.includes({{ $sIndex }}) }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                        <h3 class="font-extrabold text-slate-900 text-sm sm:text-base">
                            {{ $section->title }}
                        </h3>
                    </div>

                    <div class="text-xs font-semibold text-slate-500 flex items-center gap-3">
                        <span>{{ __('messages.lessons_count', ['count' => $section->lessons->count()]) }}</span>
                        <span class="hidden sm:inline">&bull;</span>
                        <span class="hidden sm:inline">{{ floor($section->lessons->sum('duration') / 60) }} min</span>
                    </div>
                </button>

                <!-- Lessons List -->
                <div x-show="openSections.includes({{ $sIndex }})"
                     x-transition
                     class="divide-y divide-slate-100 bg-white">
                    @forelse($section->lessons as $lesson)
                        <div class="px-6 py-3.5 flex items-center justify-between gap-4 hover:bg-orange-50/40 transition-colors">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                @if($lesson->is_free_preview || $isEnrolled)
                                    <span class="text-orange-500 font-bold text-sm">▶</span>
                                @else
                                    <span class="text-slate-400 text-xs">🔒</span>
                                @endif

                                <span class="text-sm font-medium text-slate-800 truncate">
                                    {{ $lesson->title }}
                                </span>

                                @if($lesson->is_free_preview)
                                    <button @click="$dispatch('open-preview-modal', { videoUrl: '{{ $lesson->video_url }}', title: '{{ $lesson->title }}' })"
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-orange-100 text-orange-700 font-bold text-[10px] uppercase tracking-wider hover:bg-orange-500 hover:text-white transition-colors cursor-pointer">
                                        <span>▶ {{ __('messages.free_preview_badge') }}</span>
                                    </button>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 text-xs font-semibold text-slate-400">
                                <span>{{ sprintf('%02d:%02d', floor($lesson->duration / 60), $lesson->duration % 60) }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-3 text-xs text-slate-400 italic">No lessons available</div>
                    @endforelse
                </div>

            </div>
        @empty
            <div class="p-8 text-center bg-white rounded-2xl border border-slate-200/80 text-slate-400 font-medium">
                No sections available
            </div>
        @endforelse
    </div>

</div>
