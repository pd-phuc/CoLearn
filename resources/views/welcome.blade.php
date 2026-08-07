@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="relative overflow-hidden bg-gradient-to-b from-orange-50/80 via-slate-50 to-slate-50 border-b border-slate-200/60 py-16 lg:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

            <!-- Hero Left Content -->
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-orange-100 border border-orange-200 text-orange-700 text-xs font-bold uppercase tracking-wider">
                    <span>🚀 Nền Tảng Đào Tạo Lập Trình Thực Chiến</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-[1.15]">
                    {{ __('messages.hero_title') }}
                </h1>

                <p class="text-base sm:text-lg text-slate-600 font-medium max-w-2xl mx-auto lg:mx-0 leading-relaxed">
                    {{ __('messages.hero_subtitle') }}
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                    <a href="#courses" class="btn-primary py-3.5 px-8 text-base font-bold w-full sm:w-auto shadow-lg shadow-orange-500/25">
                        {{ __('messages.explore_courses') }} &rarr;
                    </a>
                    <a href="{{ route('register') }}" class="btn-secondary py-3.5 px-6 text-base font-bold w-full sm:w-auto">
                        {{ __('auth.register_now') }}
                    </a>
                </div>

                <!-- Stats Badge Row -->
                <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-200/80 max-w-lg mx-auto lg:mx-0">
                    <div>
                        <p class="text-2xl font-black text-slate-900">10,000+</p>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Học Viên</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-slate-900">50+</p>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Khóa Học</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-orange-600">98%</p>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Hài Lòng</p>
                    </div>
                </div>
            </div>

            <!-- Hero Right Banner Card -->
            <div class="lg:col-span-5 relative">
                <div class="relative mx-auto max-w-md lg:max-w-none bg-white p-6 sm:p-8 rounded-3xl shadow-2xl shadow-orange-500/10 border border-slate-100">
                    <div class="aspect-video w-full rounded-2xl bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 p-6 flex flex-col justify-between text-white relative overflow-hidden shadow-inner">
                        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-xl"></div>
                        <div class="flex justify-between items-start z-10">
                            <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-lg text-xs font-bold uppercase tracking-wider">CoLearn Original</span>
                            <span class="text-2xl">⚡</span>
                        </div>
                        <div class="z-10">
                            <h3 class="text-xl font-black leading-tight">Lập Trình Web Laravel 13 & Fullstack</h3>
                            <p class="text-xs text-white/90 font-medium mt-1">Lộ trình thực chiến từ Zero đến Hero</p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-semibold text-slate-700">Giảng Viên Tiêu Biểu</span>
                            <span class="font-bold text-orange-600">TITV / 28Tech Style</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-orange-500 h-full w-[85%] rounded-full"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Featured Categories Section -->
@php
    $categories = \App\Models\Category::where('is_active', true)->get();
@endphp
@if($categories->count() > 0)
<section class="py-16 bg-white border-b border-slate-200/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ __('messages.featured_categories') }}
                </h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Chọn lĩnh vực bạn muốn làm chủ hôm nay</p>
            </div>
            <a href="#" class="text-sm font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                {{ __('messages.view_all') }} &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="#" class="group p-6 rounded-2xl bg-slate-50 hover:bg-orange-50/50 border border-slate-200/80 hover:border-orange-300 transition-all duration-200 hover:-translate-y-1 hover:shadow-lg hover:shadow-orange-500/5">
                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all duration-200">
                        ⚡
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg group-hover:text-orange-600 transition-colors">
                        {{ $category->name }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-1.5 line-clamp-2">
                        {{ $category->description }}
                    </p>
                    <div class="mt-4 flex items-center text-xs font-bold text-orange-600">
                        <span>{{ $category->courses()->count() }} {{ __('messages.all_courses') }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Published Courses Section -->
@php
    $courses = \App\Models\Course::with(['teacher', 'category', 'sections.lessons'])->published()->get();
@endphp
<section id="courses" class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                    {{ __('messages.featured_courses') }}
                </h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Các khóa học chất lượng cao được thiết kế bài bản</p>
            </div>
            <a href="#" class="text-sm font-bold text-orange-600 hover:text-orange-700 flex items-center gap-1">
                {{ __('messages.view_all') }} &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($courses as $course)
                <div class="bg-white rounded-3xl overflow-hidden border border-slate-200/80 shadow-md hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
                    <!-- Course Thumbnail Header -->
                    <div class="relative aspect-video bg-slate-900 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent z-10"></div>
                        <div class="w-full h-full bg-gradient-to-tr from-orange-600 to-amber-500 flex items-center justify-center text-white text-4xl font-extrabold group-hover:scale-105 transition-transform duration-300">
                            {{ strtoupper(substr($course->title, 0, 2)) }}
                        </div>
                        <span class="absolute top-3 left-3 z-20 px-3 py-1 bg-white/90 backdrop-blur-md text-slate-900 font-bold text-[11px] uppercase tracking-wider rounded-lg shadow-xs">
                            {{ $course->category->name }}
                        </span>
                        <span class="absolute bottom-3 left-3 z-20 px-2.5 py-0.5 bg-orange-500 text-white font-bold text-[10px] uppercase tracking-wider rounded-md">
                            {{ $course->level }}
                        </span>
                    </div>

                    <!-- Course Content Body -->
                    <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div>
                            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug">
                                {{ $course->title }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                                {{ $course->description }}
                            </p>
                        </div>

                        <!-- Teacher & Stats Info -->
                        <div class="space-y-3 pt-3 border-t border-slate-100">
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 font-bold flex items-center justify-center text-[10px]">
                                        {{ strtoupper(substr($course->teacher->name ?? 'G', 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-slate-700">{{ $course->teacher->name ?? 'Giảng Viên' }}</span>
                                </div>
                                <span class="font-medium text-slate-500">
                                    {{ $course->sections->flatMap->lessons->count() }} {{ __('messages.lessons_count', ['count' => '']) }}
                                </span>
                            </div>

                            <!-- Price Section -->
                            <div class="flex items-center justify-between pt-1">
                                <div>
                                    @if($course->discount_price)
                                        <span class="text-lg font-black text-orange-600">
                                            {{ number_format($course->discount_price) }} {{ __('messages.price_currency') }}
                                        </span>
                                        <span class="text-xs text-slate-400 line-through ml-1.5">
                                            {{ number_format($course->price) }} {{ __('messages.price_currency') }}
                                        </span>
                                    @elseif($course->price > 0)
                                        <span class="text-lg font-black text-slate-900">
                                            {{ number_format($course->price) }} {{ __('messages.price_currency') }}
                                        </span>
                                    @else
                                        <span class="text-lg font-black text-emerald-600">
                                            {{ __('messages.free') }}
                                        </span>
                                    @endif
                                </div>
                                <a href="#" class="btn-primary text-xs py-2 px-3.5 font-bold">
                                    Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 bg-white rounded-3xl border border-slate-200/80">
                    <p class="text-slate-500 font-medium">Đang cập nhật danh sách khóa học...</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
