<!-- Udemy Style Sticky Purchase Card -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xl p-6 space-y-6 sticky top-20">

    <!-- Video Preview Card Thumbnail -->
    <div class="relative aspect-video rounded-2xl bg-slate-900 overflow-hidden group shadow-md">
        <div class="w-full h-full bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 flex items-center justify-center text-white text-4xl font-black group-hover:scale-105 transition-transform duration-300">
            {{ strtoupper(substr($course->title, 0, 2)) }}
        </div>

        @if($freePreviewCount > 0)
            <!-- Play Video Overlay Trigger -->
            <button @click="$dispatch('open-preview-modal')"
                    class="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/20 flex flex-col items-center justify-center text-white transition-all cursor-pointer">
                <div class="w-14 h-14 rounded-full bg-orange-500 text-white flex items-center justify-center text-2xl shadow-xl group-hover:scale-110 transition-transform pl-1">
                    ▶
                </div>
                <span class="mt-2 text-xs font-extrabold uppercase tracking-wider bg-slate-900/80 px-3 py-1 rounded-full backdrop-blur-md">
                    {{ __('messages.preview_video_btn', ['count' => $freePreviewCount]) }}
                </span>
            </button>
        @endif
    </div>

    <!-- Pricing Tag & Discount Badge -->
    <div class="space-y-1">
        @if($course->discount_price)
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-orange-600">
                    {{ number_format($course->discount_price) }}{{ __('messages.price_currency') }}
                </span>
                <span class="text-sm font-medium text-slate-400 line-through">
                    {{ number_format($course->price) }}{{ __('messages.price_currency') }}
                </span>
                @if($discountPercent > 0)
                    <span class="px-2 py-0.5 bg-rose-100 text-rose-700 font-extrabold text-xs rounded-md">
                        -{{ $discountPercent }}%
                    </span>
                @endif
            </div>
        @elseif($course->price > 0)
            <span class="text-3xl font-black text-slate-900">
                {{ number_format($course->price) }}{{ __('messages.price_currency') }}
            </span>
        @else
            <span class="text-3xl font-black text-emerald-600">
                {{ __('messages.free') }}
            </span>
        @endif
    </div>

    <!-- Dynamic Action Buttons according to Enrollment State -->
    <div class="space-y-3 pt-2">
        @if($isEnrolled)
            <a href="#" class="w-full btn-primary py-3.5 text-base font-bold bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20">
                ✅ {{ __('messages.go_to_learning') }}
            </a>
        @else
            @auth
                <form action="#" method="POST">
                    @csrf
                    <button type="submit" class="w-full btn-primary py-3.5 text-base font-bold shadow-lg shadow-orange-500/25">
                        {{ $course->price > 0 ? __('messages.buy_course') : __('messages.enroll_free') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="w-full btn-primary py-3.5 text-base font-bold shadow-lg shadow-orange-500/25">
                    {{ __('messages.login_to_buy') }}
                </a>
            @endauth
        @endif
    </div>

    <!-- Guarantees & Features List -->
    <div class="border-t border-slate-100 pt-5 space-y-3 text-xs font-semibold text-slate-600">
        <p class="font-extrabold text-slate-900 uppercase tracking-wider">{{ __('messages.course_includes') }}</p>
        <div class="flex items-center gap-2.5">
            <span class="text-orange-500 font-bold">🎬</span>
            <span>{{ __('messages.4k_videos', ['duration' => $formattedDuration]) }}</span>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="text-orange-500 font-bold">📖</span>
            <span>{{ __('messages.total_lessons', ['count' => $totalLessonsCount]) }}</span>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="text-orange-500 font-bold">∞</span>
            <span>{{ __('messages.lifetime_access') }}</span>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="text-orange-500 font-bold">📱</span>
            <span>{{ __('messages.mobile_and_desktop') }}</span>
        </div>
        <div class="flex items-center gap-2.5">
            <span class="text-orange-500 font-bold">📜</span>
            <span>{{ __('messages.completion_certificate') }}</span>
        </div>
    </div>

</div>
