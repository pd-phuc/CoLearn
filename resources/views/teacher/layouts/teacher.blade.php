<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100/60">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' . __('teacher.portal') : __('teacher.portal') . ' — ' . __('messages.app_name') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    @stack('head-scripts')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-100/60 via-slate-50 to-white font-sans antialiased text-slate-900 flex flex-col justify-between selection:bg-primary-500 selection:text-white" x-data="{ mobileMenuOpen: false }">

    <div class="w-full">
        {{-- Floating Glassmorphic 2-Tier Teacher Header --}}
        <header class="sticky top-3 z-50 mx-auto w-full max-w-7xl px-4 sm:px-6">
            <div class="rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-xl shadow-md transition-all px-5 py-3 space-y-3">

                @php
                    $navItems = [
                        ['route' => 'teacher.dashboard', 'label' => __('teacher.dashboard'), 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'teacher.courses.index', 'label' => __('teacher.my_courses'), 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['route' => 'teacher.courses.create', 'label' => __('teacher.create_course'), 'icon' => 'M12 4v16m8-8H4'],
                        ['route' => 'teacher.students.index', 'label' => __('teacher.students'), 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'],
                        ['route' => 'teacher.analytics.index', 'label' => __('teacher.analytics'), 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                        ['route' => 'teacher.profile.edit', 'label' => __('teacher.profile'), 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ];
                @endphp

                {{-- Row 1: Brand Logo + Right Actions --}}
                <div class="flex items-center justify-between gap-4 pb-2.5 border-b border-slate-100">
                    {{-- Brand Logo + Teacher Badge --}}
                    <div class="flex items-center gap-2.5 shrink-0">
                        <a href="{{ route('teacher.dashboard') }}" class="group flex items-center gap-2 transition-all">
                            <div class="relative">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-200">
                                    C
                                </div>
                                <div class="absolute inset-0 rounded-xl bg-blue-500/30 blur-md opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <span class="text-xl font-extrabold tracking-tight text-slate-900">
                                Co<span class="text-blue-600">Learn</span>
                            </span>
                        </a>
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-widest rounded-lg border border-blue-200/60 shadow-2xs">
                            Teacher
                        </span>
                    </div>

                    {{-- Right Actions --}}
                    <div class="flex items-center gap-3.5 shrink-0">
                        {{-- View Site Button --}}
                        <a href="{{ route('home') }}" target="_blank" class="hidden sm:inline-flex items-center gap-2 px-3.5 py-1.5 text-sm font-bold text-slate-700 hover:text-blue-600 hover:bg-blue-50/60 rounded-xl transition-colors">
                            <x-icon name="external-link" class="w-4.5 h-4.5 text-blue-500" />
                            {{ __('teacher.view_site') }}
                        </a>

                        {{-- Language Switcher Pill --}}
                        <div class="flex items-center bg-slate-100 p-1 rounded-xl text-sm font-bold border border-slate-200/60 shrink-0">
                            <a href="{{ route('lang.switch', 'vi') }}" class="px-3 py-1 rounded-lg transition-all {{ app()->getLocale() === 'vi' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">VI</a>
                            <a href="{{ route('lang.switch', 'en') }}" class="px-3 py-1 rounded-lg transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-blue-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">EN</a>
                        </div>

                        {{-- Teacher Profile Dropdown --}}
                        <div x-data="{ open: false }" @click.outside="open = false" class="relative shrink-0">
                            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 transition-colors focus:outline-none">
                                <x-user-avatar :user="auth()->user()" size="sm" class="ring-2 ring-blue-500/30" />
                                <span class="hidden sm:inline-block text-sm font-bold text-slate-800 max-w-[140px] truncate">{{ auth()->user()->name }}</span>
                                <x-icon name="chevron-down" class="w-4 h-4 text-slate-400 hidden sm:block" />
                            </button>

                            <div x-show="open" x-transition class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-slate-200/80 py-2 z-50 divide-y divide-slate-100" style="display: none;">
                                <div class="px-4 py-3">
                                    <p class="text-sm font-extrabold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                    <div class="mt-2 flex items-center justify-between">
                                        <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 font-bold text-[10px] rounded-md uppercase tracking-wider">{{ __('teacher.teacher_role') }}</span>
                                    </div>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('teacher.profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-blue-50/80 hover:text-blue-600">
                                        <x-icon name="user" class="w-4 h-4 text-slate-400" />
                                        {{ __('teacher.profile') }}
                                    </a>
                                </div>
                                <div class="py-1">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm font-bold text-rose-600 hover:bg-rose-50">
                                            {{ __('auth.logout') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Mobile Menu Trigger --}}
                        <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-1.5 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors">
                            <x-icon name="menu" class="w-5 h-5" />
                        </button>
                    </div>
                </div>

                {{-- Row 2: Teacher Navigation Tabs --}}
                <div class="pt-0.5">
                    <nav class="hidden lg:flex items-center justify-between w-full gap-2">
                        @foreach($navItems as $item)
                            @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap {{ $isActive ? 'bg-blue-50 text-blue-700 border border-blue-200/60 shadow-2xs' : 'text-slate-700 hover:text-blue-600 hover:bg-blue-50/60 border border-transparent' }}">
                                <svg class="w-4.5 h-4.5 shrink-0 {{ $isActive ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>

                {{-- Mobile Dropdown Menu --}}
                <div x-show="mobileMenuOpen" x-transition class="lg:hidden mt-3 pt-3 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-1.5">
                    @foreach($navItems as $item)
                        @php $isActive = request()->routeIs($item['route'] . '*'); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-bold transition-all {{ $isActive ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'text-slate-600 hover:bg-slate-50' }}">
                            <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-blue-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
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
            {{-- Success Notification Banner --}}
            @if (session('status') || session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('status') ?? session('success') }}</span>
                </div>
            @endif

            {{-- Error Notification Banner --}}
            @if (session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-center gap-3 shadow-xs">
                    <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-bold">{{ session('error') }}</span>
                </div>
            @endif

            @yield('teacher-content')
        </main>
    </div>

    @include('partials.footer', ['accentColor' => 'blue'])

    @stack('scripts')
</body>
</html>
