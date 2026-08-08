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
                <span class="animate-pulse">🔥</span>
                <span>Nền tảng đào tạo chuẩn TITV & 28Tech</span>
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 tracking-tight leading-[1.15]">
                Học Lập Trình Thực Chiến — <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500">Bứt Phá Sự Nghiệp IT</span>
            </h1>

            <p class="text-base text-slate-600 font-medium max-w-xl mx-auto lg:mx-0 leading-relaxed">
                Hệ thống bài giảng chất lượng cao, bài tập tự động chấm và lộ trình thiết kế bài bản cho sinh viên IT & Lập trình viên tương lai.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                <a href="#courses" class="btn-primary py-3.5 px-8 text-base font-bold w-full sm:w-auto shadow-lg shadow-orange-500/25">
                    Khám Phá Khóa Học &rarr;
                </a>
                <a href="#learning-paths" class="btn-secondary py-3.5 px-6 text-base font-bold w-full sm:w-auto">
                    Xem Lộ Trình Học
                </a>
            </div>

            <!-- Key Metrics Bar -->
            <div class="grid grid-cols-3 gap-4 pt-8 border-t border-slate-200/80 max-w-md mx-auto lg:mx-0 text-center lg:text-left">
                <div>
                    <p class="text-2xl font-black text-slate-900">10,000+</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Học Viên</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-slate-900">50+</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Khóa Học</p>
                </div>
                <div>
                    <p class="text-2xl font-black text-orange-600">98%</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Hoàn Thành</p>
                </div>
            </div>
        </div>

        <!-- Hero Right Floating Card Container (fcode style) -->
        <div class="lg:col-span-5 relative">
            <div class="relative mx-auto max-w-md bg-white/90 backdrop-blur-xl p-6 sm:p-7 rounded-3xl shadow-2xl border border-slate-200/80">

                <!-- Floating Badge Pill -->
                <div class="absolute -top-4 -right-4 z-20 bg-gradient-to-r from-orange-500 to-amber-500 text-white px-3.5 py-1.5 rounded-full text-xs font-extrabold shadow-lg animate-floating">
                    ⭐ TOP 1 Best Seller
                </div>

                <div class="aspect-video w-full rounded-2xl bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 p-6 flex flex-col justify-between text-white shadow-inner relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <span class="px-2.5 py-1 bg-white/20 backdrop-blur-md rounded-lg text-[10px] font-bold uppercase tracking-wider">Laravel 13 Real Project</span>
                        <span class="text-2xl">💻</span>
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
                <span>📍 Lộ Trình Đào Tạo</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Lộ Trình Học Thực Chiến Bài Bản
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Path 1 -->
        <div class="group p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-black text-2xl mb-4 group-hover:scale-110 group-hover:bg-orange-500 group-hover:text-white transition-all">
                🌐
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-orange-600 transition-colors">
                Lập Trình Web Fullstack
            </h3>
            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">
                Từ HTML/CSS, Tailwind 4, Blade đến Backend Laravel 13 & PostgreSQL thực tế.
            </p>
            <span class="inline-block mt-4 text-xs font-bold text-orange-600 group-hover:translate-x-1 transition-transform">
                Xem lộ trình 4 khóa &rarr;
            </span>
        </div>

        <!-- Path 2 -->
        <div class="group p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-black text-2xl mb-4 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all">
                ⚙️
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-blue-600 transition-colors">
                C++ & Giải Thuật
            </h3>
            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">
                Cấu trúc dữ liệu, thuật toán nâng cao cho sinh viên IT & Kỳ thi tin học.
            </p>
            <span class="inline-block mt-4 text-xs font-bold text-blue-600 group-hover:translate-x-1 transition-transform">
                Xem lộ trình 3 khóa &rarr;
            </span>
        </div>

        <!-- Path 3 -->
        <div class="group p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center font-black text-2xl mb-4 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all">
                🗄️
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-purple-600 transition-colors">
                Cơ Sở Dữ Liệu Chuyên Sâu
            </h3>
            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">
                Thiết kế Database chuẩn hoá, Indexing, Tối ưu Query SQL & Caching Redis.
            </p>
            <span class="inline-block mt-4 text-xs font-bold text-purple-600 group-hover:translate-x-1 transition-transform">
                Xem lộ trình 2 khóa &rarr;
            </span>
        </div>

        <!-- Path 4 -->
        <div class="group p-6 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-black text-2xl mb-4 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                ☁️
            </div>
            <h3 class="font-extrabold text-slate-900 text-lg group-hover:text-emerald-600 transition-colors">
                DevOps & Cloud AWS
            </h3>
            <p class="text-xs text-slate-500 mt-2 font-medium leading-relaxed">
                Containerize Docker, Nginx Reverse Proxy, CI/CD Deployment trên Cloud.
            </p>
            <span class="inline-block mt-4 text-xs font-bold text-emerald-600 group-hover:translate-x-1 transition-transform">
                Xem lộ trình 2 khóa &rarr;
            </span>
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
            <p class="text-sm font-medium text-slate-500 mt-1">Khám phá theo từng chuyên mục đào tạo</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($categories as $category)
            <a href="#courses" class="group p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center justify-between">
                <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-lg group-hover:bg-orange-500 group-hover:text-white transition-colors">
                        ⚡
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm group-hover:text-orange-600 transition-colors">
                            {{ $category->name }}
                        </h4>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">
                            {{ $category->courses()->count() }} {{ __('messages.all_courses') }}
                        </p>
                    </div>
                </div>
                <span class="text-slate-300 group-hover:text-orange-500 group-hover:translate-x-1 transition-all">&rarr;</span>
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
            <p class="text-sm font-medium text-slate-500 mt-1">Danh sách các khóa học bán chạy nhất</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($courses as $course)
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col group overflow-hidden hover:-translate-y-1">

                <!-- Course Card Image Header -->
                <div class="relative aspect-video bg-slate-900 overflow-hidden">
                    <div class="w-full h-full bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 flex items-center justify-center text-white text-3xl font-black group-hover:scale-105 transition-transform duration-300">
                        {{ strtoupper(substr($course->title, 0, 2)) }}
                    </div>

                    <!-- Category Badge -->
                    <span class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-white/90 backdrop-blur-md text-slate-900 font-bold text-[11px] uppercase tracking-wider rounded-lg shadow-xs">
                        {{ $course->category->name }}
                    </span>

                    <!-- Level Badge -->
                    <span class="absolute top-3 right-3 z-10 px-2.5 py-0.5 bg-slate-900/80 backdrop-blur-md text-white font-bold text-[10px] uppercase tracking-wider rounded-md">
                        {{ $course->level }}
                    </span>
                </div>

                <!-- Course Card Body -->
                <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base group-hover:text-orange-600 transition-colors line-clamp-2 leading-snug">
                            {{ $course->title }}
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
                                <div class="flex text-amber-400 text-xs">★★★★★</div>
                                <span class="text-slate-400 text-[11px]">(120)</span>
                            </div>
                            <span class="font-bold text-slate-500">
                                📖 {{ $course->sections->flatMap->lessons->count() }} bài học
                            </span>
                        </div>

                        <!-- Teacher Info -->
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-orange-100 text-orange-600 font-bold flex items-center justify-center text-[10px]">
                                {{ strtoupper(substr($course->teacher->name ?? 'G', 0, 1)) }}
                            </div>
                            <span class="text-xs font-bold text-slate-700 truncate">{{ $course->teacher->name ?? 'Giảng Viên' }}</span>
                        </div>

                        <!-- Price Tag & CTA -->
                        <div class="flex items-center justify-between pt-2">
                            <div>
                                @if($course->discount_price)
                                    <div class="flex items-baseline gap-1.5">
                                        <span class="text-lg font-black text-orange-600">
                                            {{ number_format($course->discount_price) }}đ
                                        </span>
                                        <span class="text-xs text-slate-400 line-through font-medium">
                                            {{ number_format($course->price) }}đ
                                        </span>
                                    </div>
                                @elseif($course->price > 0)
                                    <span class="text-lg font-black text-slate-900">
                                        {{ number_format($course->price) }}đ
                                    </span>
                                @else
                                    <span class="text-lg font-black text-emerald-600">
                                        Miễn Phí
                                    </span>
                                @endif
                            </div>

                            <a href="#" class="btn-primary text-xs py-2 px-3.5 font-bold shadow-xs">
                                Xem Chi Tiết
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-2xl border border-slate-200/80">
                <p class="text-slate-500 font-medium">Đang cập nhật danh sách khóa học...</p>
            </div>
        @endforelse
    </div>
</section>

@endsection
