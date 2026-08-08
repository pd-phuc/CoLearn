@extends('layouts.app')

@section('content')

<!-- Hero Section with fcode floating aesthetics & 28Tech CTA -->
<section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500/10 via-amber-500/5 to-slate-50 border border-orange-200/60 p-8 sm:p-12 lg:p-16 mb-12 shadow-sm">
    <!-- Decorative Floating Shapes (fcode style) -->
    <div class="absolute -top-12 -right-12 w-64 h-64 bg-orange-500/10 rounded-full blur-3xl animate-pulse-glow"></div>
    <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl animate-pulse-glow"></div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center relative z-10">

        <!-- Hero Left Text -->
        <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-100 text-orange-700 border border-orange-200 text-xs font-bold uppercase tracking-wider">
                <svg class="w-4 h-4 text-orange-500 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ __('messages.hero_tagline') }}</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-[1.15]">
                {{ __('messages.hero_title') }}
            </h1>

            <p class="text-base text-slate-600 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                {{ __('messages.hero_subtitle') }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                <a href="{{ route('courses.index') }}" class="btn-primary py-3.5 px-8 text-base font-bold w-full sm:w-auto shadow-lg shadow-orange-500/25">
                    {{ __('messages.explore_courses') }} &rarr;
                </a>
                <a href="#learning-paths" class="btn-secondary py-3.5 px-6 text-base font-bold w-full sm:w-auto">
                    {{ __('messages.view_learning_paths') }}
                </a>
            </div>

            <!-- Key Metrics Bar -->
            <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-200/80 max-w-md mx-auto lg:mx-0 text-center lg:text-left">
                <div>
                    <p class="text-2xl font-black text-slate-900">10,000+</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('messages.students_stat') }}</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900">50+</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('messages.courses_stat') }}</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-orange-600">98%</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('messages.completion_stat') }}</p>
                </div>
            </div>
        </div>

        <!-- Hero Right Floating Card Container (fcode style) -->
        <div class="lg:col-span-5 relative">
            <div class="relative mx-auto max-w-md bg-white/90 backdrop-blur-xl p-6 sm:p-7 rounded-3xl shadow-2xl border border-slate-200/80">

                <!-- Floating Badge Pill -->
                <div class="absolute -top-4 -right-4 z-20 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-3.5 py-1.5 rounded-full text-xs font-extrabold shadow-lg animate-floating flex items-center gap-1.5">
                    <svg class="w-4 h-4 fill-amber-300" viewBox="0 0 24 24">
                        <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                    </svg>
                    <span>TOP 1 Best Seller</span>
                </div>

                <div class="aspect-video w-full rounded-2xl bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 p-6 flex flex-col justify-between text-white shadow-inner relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <span class="px-2.5 py-1 bg-white/20 backdrop-blur-md rounded-lg text-[10px] font-bold uppercase tracking-wider">Laravel 13 Real Project</span>
                        <svg class="w-6 h-6 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black leading-snug">Fullstack Web Titan Standard</h3>
                        <p class="text-xs text-white/90 font-medium mt-1">Nền tảng thương mại điện tử thực tế</p>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    <div class="flex items-center justify-between text-xs font-bold">
                        <span class="text-slate-700">Tiến độ lộ trình</span>
                        <span class="text-orange-600">85% Bài giảng video 4K</span>
                    </div>
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-full w-[85%] rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- 28Tech & TITV Style Learning Paths Section -->
<section id="learning-paths" class="mb-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <div class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-wider text-orange-600 mb-1">
                <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                </svg>
                <span>{{ __('messages.learning_paths') }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                {{ __('messages.learning_paths_title') }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Path 1 -->
        <div class="group p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black text-2xl mb-4 group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-orange-600 transition-colors">
                Lập Trình Web Fullstack
            </h3>
            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">
                Từ HTML/CSS, Tailwind 4, Blade đến Backend Laravel 13 & PostgreSQL thực tế.
            </p>
            <a href="{{ route('courses.index') }}" class="inline-block mt-4 text-xs font-bold text-orange-600 group-hover:translate-x-1 transition-transform">
                Xem lộ trình &rarr;
            </a>
        </div>

        <!-- Path 2 -->
        <div class="group p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-black text-2xl mb-4 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-blue-600 transition-colors">
                C++ & Giải Thuật
            </h3>
            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">
                Cấu trúc dữ liệu, thuật toán nâng cao cho sinh viên IT & Kỳ thi tin học.
            </p>
            <a href="{{ route('courses.index') }}" class="inline-block mt-4 text-xs font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                Xem lộ trình &rarr;
            </a>
        </div>

        <!-- Path 3 -->
        <div class="group p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-black text-2xl mb-4 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                </svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-purple-600 transition-colors">
                Cơ Sở Dữ Liệu Chuyên Sâu
            </h3>
            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">
                Thiết kế Database chuẩn hoá, Indexing, Tối ưu Query SQL & Caching Redis.
            </p>
            <a href="{{ route('courses.index') }}" class="inline-block mt-4 text-xs font-bold text-purple-600 group-hover:translate-x-1 transition-transform">
                Xem lộ trình &rarr;
            </a>
        </div>

        <!-- Path 4 -->
        <div class="group p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-2xl mb-4 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 001-9.999 5.002 5.002 0 00-9.78 2.096A4.001 4.001 0 003 15z" />
                </svg>
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-emerald-600 transition-colors">
                DevOps & Cloud AWS
            </h3>
            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">
                Containerize Docker, Nginx Reverse Proxy, CI/CD Deployment trên Cloud.
            </p>
            <a href="{{ route('courses.index') }}" class="inline-block mt-4 text-xs font-bold text-emerald-600 group-hover:translate-x-1 transition-transform">
                Xem lộ trình &rarr;
            </a>
        </div>
    </div>
</section>

<!-- Featured Categories Section -->
@php
    $categories = \App\Models\Category::where('is_active', true)->get();
@endphp
@if($categories->count() > 0)
<section class="mb-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                {{ __('messages.featured_categories') }}
            </h2>
            <p class="text-sm font-medium text-slate-500 mt-1">{{ __('messages.featured_categories_sub') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
            <a href="{{ route('courses.index', ['category' => $category->slug]) }}" class="group p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-lg group-hover:bg-orange-500 group-hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm group-hover:text-orange-600 transition-colors">
                            {{ $category->name }}
                        </h4>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">
                            {{ __('messages.courses_count', ['count' => $category->courses->count()]) }}
                        </p>
                    </div>
                </div>
                <svg class="w-5 h-5 text-slate-300 group-hover:text-orange-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @endforeach
    </div>
</section>
@endif

<!-- Published Courses Grid Section (Udemy + TITV hybrid card style) -->
@php
    $courses = \App\Models\Course::with(['teacher', 'category', 'sections.lessons'])->published()->get();
@endphp
<section id="courses" class="mb-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                {{ __('messages.featured_courses') }}
            </h2>
            <p class="text-sm font-medium text-slate-500 mt-1">{{ __('messages.featured_courses_sub') }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($courses as $course)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col group overflow-hidden hover:-translate-y-1">

                <!-- Course Card Image Header -->
                <div class="relative aspect-video bg-slate-900 overflow-hidden">
                    @if($course->thumbnail)
                        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 flex items-center justify-center text-white text-3xl font-black group-hover:scale-105 transition-transform duration-300">
                            {{ strtoupper(substr($course->title, 0, 2)) }}
                        </div>
                    @endif

                    <!-- Category Badge -->
                    <span class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-white/90 backdrop-blur-md text-slate-900 font-bold text-[11px] uppercase tracking-wider rounded-lg shadow-xs">
                        {{ $course->category->name }}
                    </span>

                    <!-- Level Badge -->
                    <span class="absolute top-3 right-3 z-10 px-2.5 py-0.5 bg-slate-900/80 backdrop-blur-md text-white font-bold text-[10px] uppercase tracking-wider rounded-md">
                        {{ __('messages.level_' . $course->level) }}
                    </span>
                </div>

                <!-- Course Card Body -->
                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug">
                            <a href="{{ route('courses.show', $course->slug) }}">
                                {{ $course->title }}
                            </a>
                        </h3>
                        <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed font-medium">
                            {{ $course->description }}
                        </p>
                    </div>

                    <!-- Course Meta & Rating (Udemy Style) -->
                    <div class="space-y-3 pt-3 border-t border-slate-100">

                        <!-- Rating Stars & Lessons count -->
                        <div class="flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-amber-500">5.0</span>
                                <div class="flex text-amber-400 gap-0.5">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    @endfor
                                </div>
                                <span class="text-slate-400 text-[11px]">({{ __('messages.reviews_count', ['count' => 120]) }})</span>
                            </div>
                            <span class="font-bold text-slate-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                                <span>{{ __('messages.lessons_count', ['count' => $course->sections->flatMap->lessons->count()]) }}</span>
                            </span>
                        </div>

                        <!-- Teacher Info -->
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 font-bold flex items-center justify-center text-[10px] overflow-hidden">
                                @if($course->teacher->avatar)
                                    <img src="{{ $course->teacher->avatar }}" alt="{{ $course->teacher->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($course->teacher->name ?? 'G', 0, 1)) }}
                                @endif
                            </div>
                            <span class="text-xs font-bold text-slate-700 truncate">{{ $course->teacher->name ?? 'Giảng Viên' }}</span>
                        </div>

                        <!-- Price Tag & CTA -->
                        <div class="flex items-center justify-between pt-2">
                            <div>
                                @if($course->discount_price)
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-lg font-black text-orange-600">
                                            {{ number_format($course->discount_price) }}{{ __('messages.price_currency') }}
                                        </span>
                                        <span class="text-xs text-slate-400 line-through font-medium">
                                            {{ number_format($course->price) }}{{ __('messages.price_currency') }}
                                        </span>
                                    </div>
                                @elseif($course->price > 0)
                                    <span class="text-lg font-black text-slate-900">
                                        {{ number_format($course->price) }}{{ __('messages.price_currency') }}
                                    </span>
                                @else
                                    <span class="text-lg font-black text-emerald-600">
                                        {{ __('messages.free') }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('courses.show', $course->slug) }}" class="btn-primary text-xs py-2 px-3.5 font-bold shadow-xs">
                                {{ __('messages.view_details') }}
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-slate-200/80">
                <p class="text-slate-500 font-medium">{{ __('messages.no_courses_found') }}</p>
            </div>
        @endforelse
    </div>
</section>

@endsection
