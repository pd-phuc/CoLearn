<!-- Course Detail Dark Glass Hero Banner (Udemy + fcode style) -->
<div class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 text-white rounded-3xl p-8 sm:p-10 shadow-2xl relative overflow-hidden border border-slate-800 mb-8">
    <div class="absolute -right-20 -top-20 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl"></div>

    <div class="relative z-10 space-y-4 max-w-3xl">
        <!-- Breadcrumb & Category -->
        <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
            <a href="{{ route('home') }}" class="hover:text-orange-400">{{ __('messages.home') }}</a>
            <span>/</span>
            <a href="{{ route('courses.index', ['category' => $course->category->slug]) }}" class="text-orange-400 hover:underline">
                {{ $course->category->name }}
            </a>
            <span>/</span>
            <span class="text-slate-300 truncate max-w-[200px]">{{ $course->title }}</span>
        </div>

        <!-- Course Title -->
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight leading-tight text-white">
            {{ $course->title }}
        </h1>

        <!-- Course Short Description -->
        <p class="text-sm text-slate-300 font-medium leading-relaxed">
            {{ $course->description }}
        </p>

        <!-- Meta Bar: Rating, Enrolled count, Teacher, Level -->
        <div class="flex flex-wrap items-center gap-4 text-xs pt-2">
            <!-- Level Badge -->
            <span class="px-2.5 py-1 bg-orange-500/20 text-orange-400 border border-orange-500/30 rounded-md font-bold uppercase tracking-wider">
                {{ __('messages.level_' . $course->level) }}
            </span>

            <!-- Rating Stars (Udemy Style) -->
            <div class="flex items-center gap-1.5 font-bold">
                <span class="text-amber-400">5.0</span>
                <div class="flex text-amber-400">★★★★★</div>
                <span class="text-slate-400">({{ __('messages.reviews_count', ['count' => 120]) }})</span>
            </div>

            <!-- Student Count -->
            <div class="text-slate-300 font-semibold flex items-center gap-1.5">
                <svg class="w-4 h-4 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>{{ __('messages.students_count', ['count' => number_format($course->enrollments()->count())]) }}</span>
            </div>

            <!-- Instructor Info -->
            <div class="flex items-center gap-2 border-l border-slate-800 pl-4">
                <div class="w-6 h-6 rounded-full bg-orange-500 text-white font-bold flex items-center justify-center text-[10px] overflow-hidden ring-1 ring-white/20">
                    @if($course->teacher && $course->teacher->avatar)
                        <img src="{{ $course->teacher->avatar }}" alt="{{ $course->teacher->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($course->teacher->name ?? 'G', 0, 1)) }}
                    @endif
                </div>
                <span class="font-semibold text-slate-200">{{ __('messages.instructor') }}: <span class="text-white font-bold">{{ $course->teacher->name ?? 'CoLearn Instructor' }}</span></span>
            </div>
        </div>
    </div>
</div>
