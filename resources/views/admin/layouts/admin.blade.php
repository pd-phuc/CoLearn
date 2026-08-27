<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100/60">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>
            {{ isset($title) ? $title . ' — ' . __('admin.panel') : __('admin.panel') . ' — ' . __('messages.app_name') }}
            
        </title>

        <!-- Google Fonts: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
            rel="stylesheet"
        />

        <!-- Vite Assets -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Alpine.js -->
        @stack('head-scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body
        class="min-h-screen bg-gradient-to-b from-slate-100/60 via-slate-50 to-white font-sans antialiased text-slate-900 flex flex-col justify-between selection:bg-orange-500 selection:text-white"
        x-data="{ mobileMenuOpen: false }"
    >
        <div class="w-full">
            {{-- Floating Glassmorphic 2-Tier Header --}}
            <header class="sticky top-3 z-50 mx-auto w-full max-w-7xl px-4 sm:px-6">
                <div
                    class="rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-xl shadow-md transition-all px-5 py-3 space-y-3"
                >
                    @php
                        $navItems = [
                            ['route' => 'admin.dashboard', 'label' => __('admin.dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['route' => 'admin.users.index', 'label' => __('admin.users'), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'],
                            ['route' => 'admin.courses.index', 'label' => __('admin.courses'), 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['route' => 'admin.orders.index', 'label' => __('admin.orders'), 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                            ['route' => 'admin.transactions.index', 'label' => __('admin.transactions'), 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
                            ['route' => 'admin.categories.index', 'label' => __('admin.categories'), 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z'],
                            ['route' => 'admin.coupons.index', 'label' => __('admin.coupons'), 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                            ['route' => 'admin.settings.index', 'label' => __('admin.settings'), 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                        ];
                    @endphp

                    {{-- Row 1: Brand Logo + Right Actions (Increased Font Size to text-sm) --}}
                    <div class="flex items-center justify-between gap-4 pb-2.5 border-b border-slate-100">
                        {{-- Brand Logo + Admin Badge --}}
                        <div class="flex items-center gap-2.5 shrink-0">
                            <a
                                href="{{ route('admin.dashboard') }}"
                                class="group flex items-center gap-2 transition-all"
                            >
                                <div class="relative">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-gradient-to-tr from-orange-600 to-amber-500 flex items-center justify-center text-white font-black text-xl shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform duration-200"
                                    >
                                        C
                                    </div>
                                    <div
                                        class="absolute inset-0 rounded-xl bg-orange-500/30 blur-md opacity-0 group-hover:opacity-100 transition-opacity"
                                    ></div>
                                </div>
                                <span class="text-xl font-extrabold tracking-tight text-slate-900">
                                    Co
                                    <span class="text-orange-500">Learn</span>
                                </span>
                            </a>
                            <span
                                class="px-2 py-0.5 bg-orange-50 text-orange-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-orange-200/60 shadow-2xs"
                            >
                                Admin
                            </span>
                        </div>

                        {{-- Right Actions (text-sm font-bold matching bottom row) --}}
                        <div class="flex items-center gap-3.5 shrink-0">
                            {{-- View Site Button --}}
                            <a
                                href="{{ route('home') }}"
                                target="_blank"
                                class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 text-sm font-bold text-slate-700 hover:text-orange-600 hover:bg-orange-50/60 rounded-xl transition-colors"
                            >
                                <svg
                                    class="w-4.5 h-4.5 text-orange-500"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                    />
                                </svg>
                                {{ __('admin.view_site') }}
                            </a>

                            {{-- Language Switcher Pill (text-sm font-bold) --}}
                            <div
                                class="flex items-center bg-slate-100 p-1 rounded-xl text-sm font-bold border border-slate-200/60 shrink-0"
                            >
                                <a
                                    href="{{ route('lang.switch', 'vi') }}"
                                    class="px-3 py-1 rounded-lg transition-all {{ app()->getLocale() === 'vi' ? 'bg-white text-orange-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}"
                                >
                                    VI
                                </a>
                                <a
                                    href="{{ route('lang.switch', 'en') }}"
                                    class="px-3 py-1 rounded-lg transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-orange-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}"
                                >
                                    EN
                                </a>
                            </div>

                            {{-- User Profile Dropdown (text-sm font-bold) --}}
                            <div x-data="{ open: false }" @click.outside="open = false" class="relative shrink-0">
                                <button
                                    @click="open = !open"
                                    class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 transition-colors focus:outline-none"
                                >
                                    @if (auth()->user()->avatar)
                                        <img
                                            src="{{ auth()->user()->avatar }}"
                                            alt=""
                                            class="w-9 h-9 rounded-full object-cover ring-2 ring-orange-500/30"
                                        />
                                    @else
                                        <div
                                            class="w-9 h-9 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-bold flex items-center justify-center text-sm shadow-sm"
                                        >
                                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span
                                        class="hidden sm:inline-block text-sm font-bold text-slate-800 max-w-[140px] truncate"
                                    >
                                        {{ auth()->user()->name }}
                                    </span>
                                    <svg
                                        class="w-4 h-4 text-slate-400 hidden sm:block"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </button>

                                <div
                                    x-show="open"
                                    x-transition
                                    class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-slate-200/80 py-2 z-50 divide-y divide-slate-100"
                                    style="display: none"
                                >
                                    <div class="px-4 py-3">
                                        <p class="text-sm font-extrabold text-slate-900 truncate">
                                            {{ auth()->user()->name }}
                                        </p>
                                        <p class="text-xs text-slate-500 truncate mt-0.5">
                                            {{ auth()->user()->email }}
                                        </p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <span
                                                class="px-2.5 py-0.5 bg-purple-100 text-purple-700 font-bold text-[10px] rounded-md uppercase tracking-wider"
                                            >
                                                {{ __('admin.system_admin') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="py-1">
                                        <a
                                            href="{{ route('admin.profile.edit') }}"
                                            class="flex items-center gap-2.5 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-orange-50/80 hover:text-orange-600"
                                        >
                                            <svg
                                                class="w-4 h-4 text-slate-400"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                            {{ __('admin.profile_settings') }}
                                        </a>
                                        <a
                                            href="{{ route('admin.settings.index') }}"
                                            class="flex items-center gap-2.5 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-50"
                                        >
                                            <svg
                                                class="w-4 h-4 text-purple-600"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                            </svg>
                                            {{ __('admin.settings') }}
                                        </a>
                                    </div>
                                    <div class="py-1">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="w-full text-left flex items-center px-4 py-2 text-sm font-bold text-rose-600 hover:bg-rose-50"
                                            >
                                                {{ __('auth.logout') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Mobile Menu Trigger --}}
                            <button
                                type="button"
                                @click="mobileMenuOpen = !mobileMenuOpen"
                                class="lg:hidden p-1.5 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Row 2: All 8 Admin Navigation Tabs (Distributed Evenly via flex justify-between w-full) --}}
                    <div class="pt-0.5">
                        <nav class="hidden lg:flex items-center justify-between w-full gap-2">
                            @foreach ($navItems as $item)
                                @php
                                    $isActive = request()->routeIs($item['route'] . '*');
                                @endphp

                                <a
                                    href="{{ route($item['route']) }}"
                                    class="flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap {{ $isActive ? 'bg-orange-50 text-orange-600 border border-orange-200/60 shadow-2xs' : 'text-slate-700 hover:text-orange-600 hover:bg-orange-50/60 border border-transparent' }}"
                                >
                                    <svg
                                        class="w-4.5 h-4.5 shrink-0 {{ $isActive ? 'text-orange-500' : 'text-slate-400' }}"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                    </svg>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    {{-- Mobile Dropdown Menu --}}
                    <div
                        x-show="mobileMenuOpen"
                        x-transition
                        class="lg:hidden mt-3 pt-3 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-1.5"
                    >
                        @foreach ($navItems as $item)
                            @php
                                $isActive = request()->routeIs($item['route'] . '*');
                            @endphp

                            <a
                                href="{{ route($item['route']) }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-bold transition-all {{ $isActive ? 'bg-orange-50 text-orange-600 border border-orange-200/60' : 'text-slate-600 hover:bg-slate-50' }}"
                            >
                                <svg
                                    class="w-4 h-4 shrink-0 {{ $isActive ? 'text-orange-500' : 'text-slate-400' }}"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="mx-auto max-w-7xl my-6 px-4 sm:px-6">
                @yield('admin-content')
            </main>
        </div>

        <!-- Main Shared Footer -->
        <footer class="bg-slate-950 text-slate-400 border-t border-slate-800/80 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                    <!-- Col 1: Platform Brand Info -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-xl bg-orange-500 flex items-center justify-center text-white font-black text-xl shadow-md"
                            >
                                C
                            </div>
                            <span class="text-2xl font-black text-white">
                                Co
                                <span class="text-orange-500">Learn</span>
                            </span>
                        </div>
                        <p class="text-sm text-slate-400 leading-relaxed font-medium">
                            {{ __('messages.footer_about') }}
                        </p>
                        <div class="flex items-center gap-3 text-xs font-bold text-slate-300">
                            <span class="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg">TITV Model</span>
                            <span class="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg">
                                28Tech Curriculum
                            </span>
                        </div>
                    </div>

                    <!-- Col 2: Admin Quick Links -->
                    <div>
                        <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">
                            {{ __('admin.admin_nav') }}
                        </h3>
                        <ul class="space-y-2.5 text-sm font-medium">
                            <li>
                                <a
                                    href="{{ route('admin.dashboard') }}"
                                    class="hover:text-orange-400 transition-colors"
                                >
                                    {{ __('admin.dashboard') }}
                                </a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('admin.users.index') }}"
                                    class="hover:text-orange-400 transition-colors"
                                >
                                    {{ __('admin.users') }}
                                </a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('admin.courses.index') }}"
                                    class="hover:text-orange-400 transition-colors"
                                >
                                    {{ __('admin.courses') }}
                                </a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('admin.settings.index') }}"
                                    class="hover:text-orange-400 transition-colors"
                                >
                                    {{ __('admin.settings') }}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Col 3: Categories -->
                    <div>
                        <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">
                            {{ __('messages.categories') }}
                        </h3>
                        <ul class="space-y-2.5 text-sm font-medium">
                            <li>
                                <a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">
                                    Lập Trình Web Laravel 13
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">
                                    C++ & Thuật Toán Chuyên Tin
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">
                                    Cơ Sở Dữ Liệu PostgreSQL
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">
                                    DevOps & Docker Deployment
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Col 4: Contact & Hotline -->
                    <div>
                        <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">
                            {{ __('messages.footer_contact') }}
                        </h3>
                        <div class="space-y-2 text-sm font-medium">
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg
                                    class="w-4 h-4 text-orange-500 shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                    />
                                </svg>
                                <span>support@colearn.vn</span>
                            </div>
                            <div class="flex items-center gap-2 text-slate-300">
                                <svg
                                    class="w-4 h-4 text-orange-500 shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                    />
                                </svg>
                                <span>Hotline: 1900 28Tech</span>
                            </div>
                            <p class="text-xs text-slate-500 pt-2">{{ __('messages.support_notice') }}</p>
                        </div>
                    </div>
                </div>

                <div
                    class="border-t border-slate-800/80 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4"
                >
                    <p>&copy; {{ date('Y') }} CoLearn. {{ __('messages.footer_rights') }}</p>
                    <div class="flex gap-4">
                        <a href="#" class="hover:text-slate-300">{{ __('messages.terms_of_service') }}</a>
                        <a href="#" class="hover:text-slate-300">{{ __('messages.privacy_policy') }}</a>
                    </div>
                </div>
            </div>
        </footer>

        <x-toast />

        @stack('scripts')
    </body>
</html>
