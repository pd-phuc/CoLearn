<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' . __('messages.app_name') : __('messages.app_name') . ' - ' . __('messages.tagline') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full font-sans antialiased text-slate-900 bg-slate-50 flex flex-col min-h-screen selection:bg-orange-500 selection:text-white">

    <!-- Header / Navbar -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">

                <!-- Logo & Categories Dropdown -->
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-orange-600 to-amber-500 flex items-center justify-center text-white font-extrabold text-xl shadow-md shadow-orange-500/20 group-hover:scale-105 transition-transform duration-200">
                            C
                        </div>
                        <span class="text-2xl font-black tracking-tight text-slate-900">Co<span class="text-orange-500">Learn</span></span>
                    </a>

                    <!-- Categories Alpine Dropdown -->
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative hidden lg:block">
                        <button @click="open = !open" class="inline-flex items-center gap-1.5 px-3.5 py-2 text-sm font-semibold text-slate-700 hover:text-orange-500 hover:bg-orange-50 rounded-xl transition-colors">
                            <span>{{ __('messages.categories') }}</span>
                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                             class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 overflow-hidden"
                             style="display: none;">
                            @php
                                $categories = \App\Models\Category::where('is_active', true)->get();
                            @endphp
                            @forelse($categories as $category)
                                <a href="#" class="flex items-center px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                                    {{ $category->name }}
                                </a>
                            @empty
                                <div class="px-4 py-2 text-xs text-slate-400">Chưa có danh mục</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="flex-1 max-w-md hidden md:block">
                    <form action="#" method="GET" class="relative">
                        <input type="text"
                               name="q"
                               placeholder="{{ __('messages.search_placeholder') }}"
                               class="w-full pl-10 pr-4 py-2 text-sm bg-slate-100 border border-transparent rounded-xl focus:bg-white focus:border-orange-500 focus:outline-none focus:ring-4 focus:ring-orange-500/10 transition-all">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </form>
                </div>

                <!-- Right Actions: Language Switcher & User Menu -->
                <div class="flex items-center gap-3">

                    <!-- Language Switcher Pill -->
                    <div class="flex items-center bg-slate-100 p-1 rounded-xl text-xs font-semibold">
                        <a href="{{ route('lang.switch', 'vi') }}" class="px-2.5 py-1 rounded-lg transition-colors {{ app()->getLocale() === 'vi' ? 'bg-white text-orange-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                            🇻🇳 VI
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" class="px-2.5 py-1 rounded-lg transition-colors {{ app()->getLocale() === 'en' ? 'bg-white text-orange-600 shadow-xs' : 'text-slate-500 hover:text-slate-900' }}">
                            🇬🇧 EN
                        </a>
                    </div>

                    @auth
                        <!-- Authenticated User Menu Dropdown -->
                        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" class="w-9 h-9 rounded-xl object-cover ring-2 ring-orange-500/30">
                                @else
                                    <div class="w-9 h-9 rounded-xl bg-orange-500 text-white font-bold flex items-center justify-center text-sm shadow-xs">
                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="hidden sm:block text-sm font-semibold text-slate-700 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            </button>

                            <div x-show="open"
                                 x-transition
                                 class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50"
                                 style="display: none;">
                                <div class="px-4 py-2 border-b border-slate-100">
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                                    @if(auth()->user()->isAdmin())
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-purple-100 text-purple-700 font-semibold text-[10px] rounded-md">Admin</span>
                                    @elseif(auth()->user()->isTeacher())
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-700 font-semibold text-[10px] rounded-md">Giảng Viên</span>
                                    @else
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-orange-100 text-orange-700 font-semibold text-[10px] rounded-md">Học Viên</span>
                                    @endif
                                </div>

                                <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600">
                                    {{ __('messages.my_courses') }}
                                </a>

                                @if(auth()->user()->isAdmin())
                                    <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600">
                                        {{ __('messages.admin_dashboard') }}
                                    </a>
                                @endif

                                @if(auth()->user()->isTeacher())
                                    <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-slate-700 hover:bg-orange-50 hover:text-orange-600">
                                        {{ __('messages.teacher_dashboard') }}
                                    </a>
                                @endif

                                <div class="border-t border-slate-100 my-1"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
                                        {{ __('auth.logout') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest Auth Buttons -->
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-orange-600 transition-colors">
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

    <!-- Global Alert Messages -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="bg-emerald-500 text-white py-3 px-4 shadow-md text-center text-sm font-medium relative">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="absolute right-4 top-3 text-white/80 hover:text-white">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" class="bg-rose-500 text-white py-3 px-4 shadow-md text-center text-sm font-medium relative">
            <span>{{ session('error') }}</span>
            <button @click="show = false" class="absolute right-4 top-3 text-white/80 hover:text-white">&times;</button>
        </div>
    @endif

    <!-- Main Content Body -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center text-white font-black text-lg">C</div>
                        <span class="text-xl font-extrabold text-white">Co<span class="text-orange-500">Learn</span></span>
                    </div>
                    <p class="text-sm text-slate-400 leading-relaxed">
                        {{ __('messages.footer_about') }}
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">{{ __('messages.footer_quick_links') }}</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-orange-400 transition-colors">{{ __('messages.home') }}</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">{{ __('messages.all_courses') }}</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-orange-400 transition-colors">{{ __('auth.login') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">{{ __('messages.categories') }}</h3>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Lập Trình Web</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">C++ & Thuật Toán</a></li>
                        <li><a href="#" class="hover:text-orange-400 transition-colors">Cơ Sở Dữ Liệu</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">{{ __('messages.footer_contact') }}</h3>
                    <p class="text-sm leading-relaxed">Email: support@colearn.vn</p>
                    <p class="text-sm leading-relaxed mt-1">Hotline: 1900 28Tech</p>
                </div>
            </div>

            <div class="border-t border-slate-800 mt-10 pt-6 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} CoLearn. {{ __('messages.footer_rights') }}</p>
            </div>
        </div>
    </footer>

</body>
</html>
