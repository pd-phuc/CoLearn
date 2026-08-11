<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100/60">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' . __('messages.app_name') : __('messages.app_name') . ' - ' . __('messages.tagline') }}</title>

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
<body class="min-h-screen bg-gradient-to-b from-slate-100/60 via-slate-50 to-white font-sans antialiased text-slate-900 flex flex-col justify-between selection:bg-orange-500 selection:text-white">

    <div class="w-full">
        <!-- Floating Glassmorphic Header (inspired by fcode-web-system-challenge-3) -->
        <header class="sticky top-3 z-50 mx-auto max-w-7xl px-4 sm:px-6">
            <div class="rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-xl shadow-md transition-all px-4 sm:px-6 py-3">
                <div class="flex items-center justify-between gap-4">

                    <!-- Logo with Glow Effect (fcode style) -->
                    <div class="flex items-center gap-6">
                        <a href="{{ route('home') }}" class="group flex items-center gap-3 transition-all">
                            <div class="relative">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-orange-600 to-amber-500 flex items-center justify-center text-white font-black text-xl shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform duration-200">
                                    C
                                </div>
                                <div class="absolute inset-0 rounded-xl bg-orange-500/30 blur-md opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                                Co<span class="text-orange-500">Learn</span>
                            </span>
                        </a>

                        <!-- Udemy-style Categories Mega Menu (Alpine.js) -->
                        <div x-data="{ open: false }" @click.outside="open = false" class="relative hidden xl:block">
                            <button @click="open = !open" class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-bold text-slate-700 hover:text-orange-600 hover:bg-orange-50/60 rounded-xl transition-colors">
                                <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                                </svg>
                                <span>{{ __('messages.categories') }}</span>
                                <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div x-show="open"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                                 class="absolute left-0 mt-3 w-72 bg-white rounded-2xl shadow-2xl border border-slate-200/80 py-3 z-50 divide-y divide-slate-100"
                                 style="display: none;">
                                @php
                                    $categories = \App\Models\Category::where('is_active', true)->get();
                                @endphp
                                 <div class="px-4 py-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">{{ __('messages.categories_menu') }}</div>
                                 <div class="py-1">
                                     @forelse($categories as $category)
                                         <a href="{{ route('courses.index', ['category' => $category->slug]) }}" class="flex items-center justify-between px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-orange-50/80 hover:text-orange-600 transition-colors">
                                             <span>{{ $category->name }}</span>
                                             <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-500 group-hover:bg-orange-100">{{ $category->courses()->count() }}</span>
                                         </a>
                                     @empty
                                         <div class="px-4 py-2 text-xs text-slate-400">{{ __('messages.no_categories') }}</div>
                                     @endforelse
                                 </div>
                             </div>
                         </div>

                         <!-- 28Tech & TITV Style Navigation Links -->
                         <nav class="hidden lg:flex items-center gap-1">
                             <a href="{{ route('home') }}" class="px-3.5 py-2 text-sm font-bold text-slate-700 hover:text-orange-600 hover:bg-orange-50/60 rounded-xl transition-colors">
                                 {{ __('messages.home') }}
                             </a>
                             <a href="{{ route('home') }}#learning-paths" class="px-3.5 py-2 text-sm font-bold text-slate-700 hover:text-orange-600 hover:bg-orange-50/60 rounded-xl transition-colors">
                                 {{ __('messages.learning_paths') }}
                             </a>
                             <a href="{{ route('courses.index') }}" class="px-3.5 py-2 text-sm font-bold text-slate-700 hover:text-orange-600 hover:bg-orange-50/60 rounded-xl transition-colors">
                                 {{ __('messages.courses') }}
                             </a>
                         </nav>
                     </div>

                     <!-- Search Bar (Udemy style with Keyboard hint) -->
                     <div class="flex-1 max-w-sm hidden md:block">
                         <form action="{{ route('courses.index') }}" method="GET" class="relative">
                             <input type="text"
                                    name="q"
                                    placeholder="{{ __('messages.search_placeholder') }}"
                                    class="w-full pl-10 pr-12 py-2 text-sm bg-slate-100/80 border border-slate-200/80 rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all font-medium">
                             <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                             </svg>
                             <kbd class="hidden sm:inline-block absolute right-3 top-2.5 text-[10px] font-bold text-slate-400 bg-white px-1.5 py-0.5 rounded border border-slate-200 shadow-2xs">Ctrl K</kbd>
                         </form>
                     </div>

                     <!-- Right Actions: Language Switcher & User Avatar Dropdown -->
                     <div class="flex items-center gap-3">

                         <!-- Language Switcher Pill -->
                         <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-bold border border-slate-200/60">
                             <a href="{{ route('lang.switch', 'vi') }}" class="px-2.5 py-1 rounded-lg transition-all {{ app()->getLocale() === 'vi' ? 'bg-white text-orange-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                                 VI
                             </a>
                             <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-lg transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-orange-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                                 EN
                             </a>
                         </div>

                         @auth
                             <!-- Wallet Balance Quick Pill Button -->
                             <a href="{{ route('wallet.index') }}" class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-orange-50 hover:bg-orange-100 text-orange-700 font-extrabold text-xs rounded-xl border border-orange-200/80 transition-colors shadow-2xs cursor-pointer">
                                 <svg class="w-3.5 h-3.5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                 </svg>
                                 <span>{{ number_format(auth()->user()->balance, 0, ',', '.') }} VNĐ</span>
                             </a>

                             <!-- Logged In User Avatar Dropdown (fcode style) -->
                             <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                                 <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-full hover:bg-slate-100 transition-colors focus:outline-none">
                                     @if(auth()->user()->avatar)
                                         <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-orange-500/30">
                                     @else
                                         <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-orange-500 to-amber-500 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                                             {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                         </div>
                                     @endif
                                     <span class="hidden sm:block text-sm font-bold text-slate-800 max-w-[110px] truncate">{{ auth()->user()->name }}</span>
                                     <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                     </svg>
                                 </button>

                                 <div x-show="open"
                                      x-transition
                                      class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-2xl border border-slate-200/80 py-2 z-50 divide-y divide-slate-100"
                                      style="display: none;">
                                     <div class="px-4 py-3">
                                         <p class="text-sm font-extrabold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                         <p class="text-xs text-slate-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                         <div class="mt-2 flex items-center justify-between">
                                             @if(auth()->user()->isAdmin())
                                                 <span class="px-2.5 py-0.5 bg-purple-100 text-purple-700 font-bold text-[10px] rounded-md uppercase tracking-wider">{{ __('messages.role_admin') }}</span>
                                             @elseif(auth()->user()->isTeacher())
                                                 <span class="px-2.5 py-0.5 bg-blue-100 text-blue-700 font-bold text-[10px] rounded-md uppercase tracking-wider">{{ __('messages.role_teacher') }}</span>
                                             @else
                                                 <span class="px-2.5 py-0.5 bg-orange-100 text-orange-700 font-bold text-[10px] rounded-md uppercase tracking-wider">{{ __('messages.role_student') }}</span>
                                             @endif

                                             <span class="text-xs font-extrabold text-emerald-600">
                                                 {{ number_format(auth()->user()->balance, 0, ',', '.') }} VNĐ
                                             </span>
                                         </div>
                                     </div>

                                    <div class="py-1">
                                        <a href="{{ route('wallet.index') }}" class="flex items-center justify-between px-4 py-2 text-sm font-bold text-emerald-700 hover:bg-emerald-50">
                                            <span class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                {{ __('messages.my_wallet') }}
                                            </span>
                                            <span class="text-[10px] font-black bg-emerald-100 px-2 py-0.5 rounded-full text-emerald-800">{{ __('messages.topup_badge') }}</span>
                                        </a>

                                        <a href="{{ route('profile.my-courses') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-orange-50/80 hover:text-orange-600">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                            </svg>
                                            {{ __('messages.my_courses') }}
                                        </a>

                                        <a href="{{ route('orders.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-orange-50/80 hover:text-orange-600">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                            {{ __('messages.my_orders') }}
                                        </a>

                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-orange-50/80 hover:text-orange-600">
                                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ __('messages.profile_settings') }}
                                        </a>

                                        @if(auth()->user()->isAdmin())
                                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-50">
                                                <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                {{ __('messages.admin_dashboard') }}
                                            </a>
                                        @endif

                                        @if(auth()->user()->isTeacher())
                                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-50">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                                </svg>
                                                {{ __('messages.teacher_dashboard') }}
                                            </a>
                                        @endif
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
                        @else
                            <!-- Guest Pill Buttons -->
                            <a href="{{ route('login') }}" class="px-3.5 py-2 text-sm font-bold text-slate-700 hover:text-orange-600 transition-colors">
                                {{ __('auth.login') }}
                            </a>
                            <a href="{{ route('register') }}" class="btn-primary text-sm px-4 py-2">
                                {{ __('auth.register') }}
                            </a>
                        @endauth

                    </div>
                </div>
            </div>
        </header>

        <!-- Global Alert Notifications -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 mt-3">
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-emerald-500 text-white rounded-xl py-3 px-5 shadow-lg text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="bg-rose-500 text-white rounded-xl py-3 px-5 shadow-lg text-sm font-semibold flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white font-bold">&times;</button>
                </div>
            @endif
        </div>

        <!-- Main Content Area -->
        <main class="mx-auto max-w-7xl my-6 px-4 sm:px-6">
            @yield('content')
        </main>
    </div>

    <!-- Footer (Udemy + 28Tech Style) -->
    <footer class="bg-slate-950 text-slate-400 border-t border-slate-800/80 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

                <!-- Col 1: Platform Brand Info -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-orange-500 flex items-center justify-center text-white font-black text-xl shadow-md">C</div>
                        <span class="text-2xl font-black text-white">Co<span class="text-orange-500">Learn</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed font-medium">
                        {{ __('messages.footer_about') }}
                    </p>
                    <div class="flex items-center gap-3 text-xs font-bold text-slate-300">
                        <span class="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg">TITV Model</span>
                        <span class="px-2.5 py-1 bg-slate-900 border border-slate-800 rounded-lg">28Tech Curriculum</span>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">{{ __('messages.footer_quick_links') }}</h3>
                    <ul class="space-y-2.5 text-sm font-medium">
                        <li><a href="{{ route('home') }}" class="hover:text-orange-400 transition-colors">{{ __('messages.home') }}</a></li>
                        <li><a href="#learning-paths" class="hover:text-orange-400 transition-colors">{{ __('messages.practical_learning_paths') }}</a></li>
                        <li><a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">{{ __('messages.all_courses') }}</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-orange-400 transition-colors">{{ __('auth.login') }}</a></li>
                    </ul>
                </div>

                <!-- Col 3: Categories -->
                <div>
                    <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">{{ __('messages.categories') }}</h3>
                    <ul class="space-y-2.5 text-sm font-medium">
                        <li><a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">Lập Trình Web Laravel 13</a></li>
                        <li><a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">C++ & Thuật Toán Chuyên Tin</a></li>
                        <li><a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">Cơ Sở Dữ Liệu PostgreSQL</a></li>
                        <li><a href="{{ route('courses.index') }}" class="hover:text-orange-400 transition-colors">DevOps & Docker Deployment</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact & Hotline -->
                <div>
                    <h3 class="text-xs font-extrabold text-white uppercase tracking-wider mb-4">{{ __('messages.footer_contact') }}</h3>
                    <div class="space-y-2 text-sm font-medium">
                        <div class="flex items-center gap-2 text-slate-300">
                            <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span>support@colearn.vn</span>
                        </div>
                        <div class="flex items-center gap-2 text-slate-300">
                            <svg class="w-4 h-4 text-orange-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>Hotline: 1900 28Tech</span>
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

    @stack('scripts')
</body>
</html>
