@extends('layouts.app')

@section('content')
<div class="mb-16">

    <!-- Hero Banner Component -->
    @include('courses.partials.hero-banner')

    <!-- Main Grid: Left Details vs Right Sticky Card -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Left Content Column -->
        <main class="lg:col-span-8 space-y-10">

            <!-- What You'll Learn Box (card-fcode) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs space-y-4">
                <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="text-orange-500">🎯</span>
                    <span>Bạn sẽ học được gì trong khóa học này?</span>
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm font-semibold text-slate-700 pt-2">
                    <div class="flex items-start gap-2.5">
                        <span class="text-emerald-500 font-bold mt-0.5">✓</span>
                        <span>Nắm vững tư duy lập trình thực chiến chuẩn 28Tech</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-emerald-500 font-bold mt-0.5">✓</span>
                        <span>Xây dựng dự án thực tế từ đầu đến triển khai Production</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-emerald-500 font-bold mt-0.5">✓</span>
                        <span>Tối ưu hóa hiệu năng Database & Caching Redis</span>
                    </div>
                    <div class="flex items-start gap-2.5">
                        <span class="text-emerald-500 font-bold mt-0.5">✓</span>
                        <span>Nhận chứng chỉ hoàn thành xuất sắc từ CoLearn</span>
                    </div>
                </div>
            </div>

            <!-- Curriculum Accordion Component -->
            @include('courses.partials.curriculum-accordion')

            <!-- Instructor Profile Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 p-8 shadow-xs space-y-4">
                <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="text-orange-500">👨‍🏫</span>
                    <span>Giảng Viên Đào Tạo</span>
                </h2>
                <div class="flex items-start gap-4 pt-2">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-black flex items-center justify-center text-xl shadow-md flex-shrink-0">
                        {{ strtoupper(substr($course->teacher->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-extrabold text-slate-900">{{ $course->teacher->name ?? 'Giảng Viên CoLearn' }}</h3>
                        <p class="text-xs font-bold text-orange-600">Chuyên gia Lập trình Senior Software Engineer</p>
                        <p class="text-xs text-slate-500 leading-relaxed pt-1">
                            Hơn 8 năm kinh nghiệm giảng dạy và phát triển ứng dụng quy mô lớn cho hàng chục ngàn học viên.
                        </p>
                    </div>
                </div>
            </div>

        </main>

        <!-- Right Sticky Purchase Card Column -->
        <aside class="lg:col-span-4">
            @include('courses.partials.sticky-purchase-card')
        </aside>

    </div>

</div>

<!-- Free Preview Video Modal Component -->
@include('courses.partials.preview-modal')

@endsection
