<!-- Udemy Style Sticky Purchase Card -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xl p-6 space-y-6 sticky top-20">

    <!-- Video Preview Card Thumbnail -->
    <div class="relative aspect-video rounded-2xl bg-slate-900 overflow-hidden group shadow-md">
        @if($course->thumbnail)
            <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 flex items-center justify-center text-white text-4xl font-black group-hover:scale-105 transition-transform duration-300">
                {{ strtoupper(substr($course->title, 0, 2)) }}
            </div>
        @endif

        @if($freePreviewCount > 0)
            <!-- Play Video Overlay Trigger -->
            <button @click="$dispatch('open-preview-modal')"
                    class="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/20 flex flex-col items-center justify-center text-white transition-all cursor-pointer">
                <div class="w-14 h-14 rounded-full bg-orange-500 text-white flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 fill-current text-white ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
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
            <a href="#" class="w-full btn-primary py-3.5 text-base font-bold bg-emerald-600 hover:bg-emerald-700 shadow-emerald-500/20 flex items-center justify-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ __('messages.go_to_learning') }}</span>
            </a>
        @else
            @auth
                <form action="{{ route('cart.add', $course) }}" method="POST">
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
            <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
            <span>{{ __('messages.4k_videos', ['duration' => $formattedDuration]) }}</span>
        </div>

        <div class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>{{ __('messages.total_lessons', ['count' => $totalLessonsCount]) }}</span>
        </div>

        <div class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ __('messages.lifetime_access') }}</span>
        </div>

        <div class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span>{{ __('messages.mobile_and_desktop') }}</span>
        </div>

        <div class="flex items-center gap-2.5">
            <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span>{{ __('messages.completion_certificate') }}</span>
        </div>
    </div>

</div>
