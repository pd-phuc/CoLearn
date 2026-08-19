{{-- Shared Footer Partial --}}
@php
    $accentColor = $accentColor ?? 'orange';
    $footerCategories = \App\Models\Category::where('is_active', true)->take(4)->get();
@endphp

<footer class="bg-slate-950 text-slate-400 border-t border-slate-800/80 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            <!-- Col 1: Platform Brand Info -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-{{ $accentColor }}-500 flex items-center justify-center text-white font-black text-xl shadow-md">C</div>
                    <span class="text-2xl font-black text-white">Co<span class="text-{{ $accentColor }}-500">Learn</span></span>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed font-medium">
                    {{ __('messages.footer_about') }}
                </p>
            </div>

            <!-- Col 2: Quick Links -->
            <div>
                <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">{{ __('messages.footer_quick_links') }}</h3>
                <ul class="space-y-2.5 text-sm font-medium">
                    <li><a href="{{ route('home') }}" class="hover:text-{{ $accentColor }}-400 transition-colors">{{ __('messages.home') }}</a></li>
                    <li><a href="{{ route('courses.index') }}" class="hover:text-{{ $accentColor }}-400 transition-colors">{{ __('messages.all_courses') }}</a></li>
                    @auth
                        @if(auth()->user()->hasRole('teacher'))
                            <li><a href="{{ route('teacher.dashboard') }}" class="hover:text-{{ $accentColor }}-400 transition-colors">{{ __('teacher.portal') }}</a></li>
                        @endif
                    @endauth
                </ul>
            </div>

            <!-- Col 3: Categories (Dynamic) -->
            <div>
                <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">{{ __('messages.categories') }}</h3>
                <ul class="space-y-2.5 text-sm font-medium">
                    @forelse($footerCategories as $category)
                        <li><a href="{{ route('courses.index', ['category' => $category->slug]) }}" class="hover:text-{{ $accentColor }}-400 transition-colors">{{ $category->name }}</a></li>
                    @empty
                        <li class="text-xs text-slate-500">{{ __('messages.no_categories') }}</li>
                    @endforelse
                </ul>
            </div>

            <!-- Col 4: Contact & Support -->
            <div>
                <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">{{ __('messages.footer_contact') }}</h3>
                <div class="space-y-2 text-sm font-medium">
                    <div class="flex items-center gap-2 text-slate-300">
                        <svg class="w-4 h-4 text-{{ $accentColor }}-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span>support@colearn.vn</span>
                    </div>
                    <p class="text-xs text-slate-500 pt-2">{{ __('messages.support_notice') }}</p>
                </div>
            </div>

        </div>

        <div class="border-t border-slate-800/80 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <p>&copy; {{ date('Y') }} CoLearn. {{ __('messages.footer_rights') }}</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-slate-300">{{ __('messages.terms_of_service') }}</a>
                <a href="#" class="hover:text-slate-300">{{ __('messages.privacy_policy') }}</a>
            </div>
        </div>
    </div>
</footer>
