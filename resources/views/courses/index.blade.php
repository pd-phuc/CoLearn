@extends('layouts.app')

@section('content')
<div class="mb-12">
    <!-- Header Banner -->
    <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-slate-800 to-orange-950 p-8 sm:p-12 text-white mb-8 shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-orange-500/10 rounded-full blur-3xl"></div>
        <div class="relative z-10 max-w-2xl space-y-3">
            <span class="px-3 py-1 bg-orange-500/20 text-orange-400 border border-orange-500/30 rounded-full text-xs font-extrabold uppercase tracking-wider">
                {{ __('messages.catalog_library') }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                {{ __('messages.catalog_title') }}
            </h1>
            <p class="text-sm text-slate-300 font-medium">
                {{ __('messages.catalog_sub') }}
            </p>
        </div>
    </div>

    <!-- Catalog Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Sidebar Filter (fcode style card) -->
        <aside class="lg:col-span-3 space-y-6">
            <form action="{{ route('courses.index') }}" method="GET" class="bg-white rounded-2xl border border-slate-200/80 p-6 shadow-sm space-y-6">
                <!-- Search Input -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">{{ __('messages.filter_search') }}</label>
                    <input type="text"
                           name="q"
                           value="{{ request('q') }}"
                           placeholder="{{ __('messages.search_placeholder') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-orange-500 focus:outline-none transition-all">
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">{{ __('messages.filter_category') }}</label>
                    <select name="category" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-orange-500 focus:outline-none transition-all">
                        <option value="">{{ __('messages.filter_all_categories') }}</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->courses_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Level Filter -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">{{ __('messages.filter_level') }}</label>
                    <select name="level" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-orange-500 focus:outline-none transition-all">
                        <option value="">{{ __('messages.filter_all_levels') }}</option>
                        <option value="beginner" {{ request('level') === 'beginner' ? 'selected' : '' }}>{{ __('messages.level_beginner') }}</option>
                        <option value="intermediate" {{ request('level') === 'intermediate' ? 'selected' : '' }}>{{ __('messages.level_intermediate') }}</option>
                        <option value="advanced" {{ request('level') === 'advanced' ? 'selected' : '' }}>{{ __('messages.level_advanced') }}</option>
                    </select>
                </div>

                <!-- Price Filter -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">{{ __('messages.filter_price') }}</label>
                    <select name="price" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-orange-500 focus:outline-none transition-all">
                        <option value="">{{ __('messages.filter_all_prices') }}</option>
                        <option value="free" {{ request('price') === 'free' ? 'selected' : '' }}>{{ __('messages.free') }}</option>
                        <option value="paid" {{ request('price') === 'paid' ? 'selected' : '' }}>{{ __('messages.filter_paid') }}</option>
                    </select>
                </div>

                <!-- Sort Filter -->
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-2">{{ __('messages.filter_sort') }}</label>
                    <select name="sort" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:bg-white focus:border-orange-500 focus:outline-none transition-all">
                        <option value="newest" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>{{ __('messages.sort_newest') }}</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>{{ __('messages.sort_popular') }}</option>
                        <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>{{ __('messages.sort_price_low') }}</option>
                        <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>{{ __('messages.sort_price_high') }}</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-2 pt-2">
                    <button type="submit" class="w-full btn-primary py-2.5 text-xs font-bold shadow-xs">
                        {{ __('messages.filter_apply') }}
                    </button>
                    @if(request()->anyFilled(['q', 'category', 'level', 'price', 'sort']))
                        <a href="{{ route('courses.index') }}" class="block text-center text-xs font-bold text-slate-400 hover:text-slate-600 py-1">
                            {{ __('messages.filter_reset') }}
                        </a>
                    @endif
                </div>
            </form>
        </aside>

        <!-- Main Courses Grid Column -->
        <main class="lg:col-span-9 space-y-6">
            <div class="flex items-center justify-between">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ __('messages.showing_courses_count', ['total' => $courses->total()]) }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($courses as $course)
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col group overflow-hidden hover:-translate-y-1">
                        <div class="relative aspect-video bg-slate-900 overflow-hidden">
                            @if($course->thumbnail)
                                <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full bg-gradient-to-tr from-orange-600 via-amber-500 to-orange-400 flex items-center justify-center text-white text-3xl font-black group-hover:scale-105 transition-transform duration-300">
                                    {{ strtoupper(substr($course->title, 0, 2)) }}
                                </div>
                            @endif
                            <span class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-white/90 backdrop-blur-md text-slate-900 font-bold text-[11px] uppercase tracking-wider rounded-lg shadow-xs">
                                {{ $course->category->name }}
                            </span>
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
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

                            <div class="space-y-3 pt-3 border-t border-slate-100">
                                <div class="flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-1">
                                        <span class="font-bold text-amber-500">5.0</span>
                                        <span class="text-amber-400">★</span>
                                    </div>
                                    <span class="font-bold text-slate-500">
                                        📖 {{ __('messages.lessons_count', ['count' => $course->sections->flatMap->lessons->count()]) }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between pt-1">
                                    <div>
                                        @if($course->discount_price)
                                            <span class="text-base font-black text-orange-600">{{ number_format($course->discount_price) }}{{ __('messages.price_currency') }}</span>
                                            <span class="text-xs text-slate-400 line-through ml-1">{{ number_format($course->price) }}{{ __('messages.price_currency') }}</span>
                                        @elseif($course->price > 0)
                                            <span class="text-base font-black text-slate-900">{{ number_format($course->price) }}{{ __('messages.price_currency') }}</span>
                                        @else
                                            <span class="text-base font-black text-emerald-600">{{ __('messages.free') }}</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('courses.show', $course->slug) }}" class="btn-primary text-xs py-2 px-3 font-bold">
                                        {{ __('messages.view_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-slate-200/80">
                        <p class="text-slate-500 font-bold text-base">{{ __('messages.no_courses_found') }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ __('messages.no_courses_sub') }}</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Links -->
            <div class="pt-6">
                {{ $courses->links() }}
            </div>
        </main>
    </div>
</div>
@endsection
