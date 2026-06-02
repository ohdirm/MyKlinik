<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — MyKlinik911')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-surface dark:bg-[#141b18] min-h-screen font-sans text-gray-900 dark:text-gray-100 transition-colors duration-200" x-data="{ sidebarOpen: window.innerWidth >= 1024, darkMode: document.documentElement.classList.contains('dark') }">
    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar overlay (mobile) --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-30 lg:hidden" x-transition.opacity></div>

        {{-- Sidebar --}}
        <aside 
            :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0 lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-40 bg-gradient-to-b from-brand-dark to-[#4e6d5e] text-white transition-all duration-300 ease-in-out lg:static lg:inset-0 flex flex-col shadow-xl overflow-hidden shrink-0"
        >
            <div class="w-64 flex flex-col h-full shrink-0">
                {{-- Logo (Always use light logo on dark sidebar) --}}
                <div class="flex items-center px-6 h-20 border-b border-white/10 shrink-0 bg-transparent">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center hover:opacity-90 transition-opacity">
                        <img src="{{ asset('assets/logodark_app.png') }}" alt="MyKlinik911 Logo" class="h-28 w-auto drop-shadow-sm">
                    </a>
                </div>

                {{-- Menu Navigation --}}
                <nav class="flex-1 py-6 space-y-8 overflow-y-auto !bg-transparent !border-none custom-scrollbar">
                    {{-- Main Group --}}
                    <div class="px-4">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em] px-3 mb-3">Menu Utama</p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-2.25-2.25v-2.25z"/></svg>
                                Dashboard
                            </a>
                            <a href="{{ route('admin.bookings.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.bookings.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.bookings.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                                Kelola Booking
                            </a>
                            <a href="{{ route('admin.archive.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.archive.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.archive.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
                                Arsip Booking
                            </a>
                            <a href="{{ route('admin.reviews.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.reviews.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.reviews.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                                Moderasi Review
                            </a>
                        </div>
                    </div>

                    {{-- Management Group --}}
                    <div class="px-4">
                        <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em] px-3 mb-3">Manajemen Klinik</p>
                        <div class="space-y-1">
                            <a href="{{ route('admin.doctor-status.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.doctor-status.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.doctor-status.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.651a3.75 3.75 0 010-5.303m5.304 0a3.75 3.75 0 010 5.303m-7.425 2.122a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.808-3.808-9.98 0-13.789m13.788 0c3.808 3.808 3.808 9.981 0 13.79M12 12h.008v.007H12V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                Status Dokter
                            </a>
                            <a href="{{ route('admin.doctors.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.doctors.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.doctors.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                Daftar Dokter
                            </a>
                            <a href="{{ route('admin.specializations.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.specializations.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.specializations.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                Spesialisasi
                            </a>
                            <a href="{{ route('admin.schedules.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.schedules.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.schedules.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                Jadwal Dokter
                            </a>
                            @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.staff.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.staff.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.staff.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                                Kelola Staff
                            </a>
                            <a href="{{ route('admin.activity-logs.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 {{ request()->routeIs('admin.activity-logs.*') ? 'bg-white/20 text-white shadow-sm' : 'text-white/70 hover:bg-white/10 hover:text-white hover:translate-x-1' }}">
                                <svg class="w-5 h-5 transition-colors group-hover:text-white {{ request()->routeIs('admin.activity-logs.*') ? 'text-white' : 'text-white/60' }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.25c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                                Log Aktivitas
                            </a>
                            @endif
                        </div>
                    </div>
                </nav>
            </div>
        </aside>

        {{-- Main area --}}
        <div class="flex-1 flex flex-col overflow-hidden bg-gray-50 dark:bg-[#141b18]">
            {{-- Topbar (Glassmorphism) --}}
            <header class="bg-white/80 dark:bg-[#1c2622]/80 backdrop-blur-md border-b border-[#e2efe7] dark:border-[#283731] h-20 flex items-center justify-between px-6 shrink-0 transition-all duration-300 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-xl hover:bg-brand-light dark:hover:bg-brand/10 text-brand dark:text-white transition-all focus:outline-none ring-1 ring-brand/10" aria-label="Toggle Navigation">
                        <svg class="w-6 h-6 transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    {{-- Page Title Section --}}
                    <div class="hidden md:flex flex-col leading-tight">
                        <h2 class="text-lg font-extrabold text-brand-dark dark:text-white tracking-tight">@yield('page_title', 'Dashboard')</h2>
                        <div class="flex items-center gap-1.5 text-[10px] sm:text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">
                            <span class="hover:text-brand transition-colors">Admin</span>
                            <svg class="w-2 h-2 text-gray-300" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="2" /></svg>
                            <span class="text-brand/80">@yield('page_subtitle', 'Ringkasan Sistem')</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Theme Toggle --}}
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', darkMode)" class="p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition-all cursor-pointer border border-transparent hover:border-gray-200 dark:hover:border-gray-700" aria-label="Toggle theme">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M3 12h2.25m-.386-6.364l1.591 1.591M12 18.75a6.75 6.75 0 110-13.5 6.75 6.75 0 010 13.5z"/></svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    </button>

                    <div class="h-8 w-[1px] bg-gray-200 dark:bg-gray-700 mx-1"></div>

                    {{-- User Profile Dropdown --}}
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" @click.away="profileOpen = false" class="flex items-center gap-3 p-1 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all cursor-pointer group">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-brand-dark flex items-center justify-center text-white font-bold shadow-sm shadow-brand/20 group-hover:scale-105 transition-transform">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="hidden lg:block text-left leading-tight pr-2">
                                <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ auth()->user()->name ?? 'Administrator' }}</p>
                                <p class="text-[10px] text-brand/80 font-bold uppercase tracking-wider">{{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin Staff' }}</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-brand transition-colors" :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        {{-- Dropdown Menu --}}
                        <div x-show="profileOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
                             class="absolute right-0 mt-3 w-56 bg-white dark:bg-[#1c2622] rounded-2xl shadow-2xl shadow-brand/10 border border-gray-100 dark:border-gray-800 py-2 z-50 overflow-hidden"
                             style="display: none;">
                            <div class="px-4 py-3 bg-gray-50/50 dark:bg-gray-800/20 border-b border-gray-100 dark:border-gray-800/50">
                                <p class="text-xs text-gray-500 font-medium">Masuk sebagai</p>
                                <p class="text-sm font-bold text-brand truncate">{{ auth()->user()->email ?? 'admin@myklinik.id' }}</p>
                            </div>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-3 w-full px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors text-left cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Content area with subtle background pattern --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 relative">
                <div class="absolute inset-x-0 top-0 h-48 bg-gradient-to-b from-brand/5 to-transparent pointer-events-none"></div>

                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-5 py-4 rounded-2xl text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300 shadow-sm shadow-emerald-100/50">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="relative z-10">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>