<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="MyKlinik911 — Sistem Pendaftaran Online Klinik. Buat janji temu dengan dokter pilihan Anda secara mudah dan cepat.">
    <title>@yield('title', 'MyKlinik911 — Pendaftaran Online Klinik')</title>
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
<body class="min-h-screen font-sans flex flex-col transition-colors duration-300" x-data="{ darkMode: document.documentElement.classList.contains('dark') }">

    {{-- Navbar --}}
    <nav class="bg-white/80 dark:bg-gray-950/80 text-gray-900 dark:text-white shadow-sm sticky top-0 z-40 backdrop-blur-md border-b border-gray-100 dark:border-gray-800 transition-colors duration-300" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('assets/logo_app.png') }}" alt="MyKlinik911" class="w-32 md:w-40 drop-shadow-sm">
                    <span class="font-bold text-xl tracking-tight hidden sm:inline-block">MyKlinik911</span>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all active:scale-95 {{ request()->is('/') ? 'bg-brand/10 text-brand' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand dark:hover:text-white' }}">Beranda</a>
                    @auth
                        @if(Auth::user()->isPatient())
                            <a href="{{ route('booking.index') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all active:scale-95 {{ request()->is('booking') ? 'bg-brand/10 text-brand' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand dark:hover:text-white' }}">Booking</a>
                            <a href="{{ route('status-dokter') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all active:scale-95 {{ request()->is('status-dokter') ? 'bg-brand/10 text-brand' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand dark:hover:text-white' }}">Status Dokter</a>
                            <a href="{{ route('patient.dashboard') }}" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all active:scale-95 {{ request()->is('antrean-saya') ? 'bg-brand/10 text-brand' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-brand dark:hover:text-white' }}">Antrean Saya</a>
                        @endif
                    @endauth

                    {{-- Auth buttons --}}
                    @guest
                        <a href="{{ route('login') }}" class="ml-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">Masuk</a>
                        <a href="{{ route('register') }}" class="px-6 py-2 rounded-xl text-sm font-semibold bg-accent hover:bg-accent-dark text-white transition-all shadow-sm shadow-accent/20">Daftar</a>
                    @else
                        {{-- 🔔 Notification Bell --}}
                        @if(Auth::user()->isPatient())
                        <div class="relative ml-2" x-data="notificationBell()" x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)">
                            <button @click="open = !open; if(open) fetchNotifications()" class="relative p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white transition-all cursor-pointer" aria-label="Notifikasi">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                <span x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center text-[10px] font-black text-white bg-red-500 rounded-full px-1 shadow-lg animate-pulse" style="display: none;"></span>
                            </button>

                            {{-- Dropdown --}}
                            <div x-show="open" @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-100"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 z-50 overflow-hidden" style="display: none;">

                                {{-- Header --}}
                                <div class="px-5 py-3.5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/80 dark:bg-gray-950/80">
                                    <h3 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                                        🔔 Notifikasi
                                        <span x-show="unreadCount > 0" x-text="unreadCount" class="text-[10px] font-black bg-red-500 text-white px-1.5 py-0.5 rounded-full" style="display: none;"></span>
                                    </h3>
                                    <button x-show="unreadCount > 0" @click="markAllAsRead()" class="text-xs text-brand hover:text-blue-700 font-semibold cursor-pointer transition-colors" style="display: none;">
                                        Tandai Semua Dibaca
                                    </button>
                                </div>

                                {{-- List --}}
                                <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-800">
                                    <template x-if="notifications.length === 0">
                                        <div class="px-5 py-10 text-center">
                                            <div class="w-14 h-14 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-7 h-7 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                                            </div>
                                            <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada notifikasi</p>
                                        </div>
                                    </template>

                                    <template x-for="notif in notifications" :key="notif.id">
                                        <div @click="markAsRead(notif.id); notif.read = true"
                                             class="px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 cursor-pointer transition-colors relative"
                                             :class="notif.read ? 'opacity-60' : ''">
                                            <div class="flex gap-3">
                                                <div class="text-xl shrink-0 mt-0.5" x-text="notif.icon"></div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2 mb-0.5">
                                                        <p class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="notif.title"></p>
                                                        <span x-show="!notif.read" class="w-2 h-2 rounded-full bg-brand shrink-0 animate-pulse"></span>
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed" x-text="notif.message"></p>
                                                    <p class="text-[10px] text-gray-400 dark:text-gray-600 mt-1.5 font-medium" x-text="notif.time"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Footer --}}
                                <div x-show="notifications.length > 0" class="px-5 py-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50/80 dark:bg-gray-950/80 text-center" style="display: none;">
                                    <a href="{{ route('patient.dashboard') }}" class="text-xs font-semibold text-brand hover:text-blue-700 transition-colors">Lihat Dashboard →</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="relative ml-2" x-data="{ dropdown: false }">
                            <button @click="dropdown = !dropdown" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all cursor-pointer">
                                <div class="w-8 h-8 bg-brand/10 dark:bg-brand/20 text-brand rounded-full flex items-center justify-center text-xs font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                            <div x-show="dropdown" @click.away="dropdown = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 mt-2 w-52 bg-white dark:bg-gray-800 rounded-2xl shadow-xl py-2 z-50 border border-gray-100 dark:border-gray-700">
                                @if(Auth::user()->isPatient())
                                    <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4 opacity-50" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Dashboard
                                    </a>
                                @endif
                                <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest

                    {{-- Theme Toggle --}}
                    <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', darkMode)" class="ml-2 p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 hover:text-brand dark:hover:text-white transition-all cursor-pointer" aria-label="Toggle theme">
                        <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M3 12h2.25m-.386-6.364l 1.591 1.591M12 18.75a6.75 6.75 0 110-13.5 6.75 6.75 0 010 13.5z"/></svg>
                        <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                    </button>
                </div>

                {{-- Mobile hamburger --}}
                <button @click="open = !open" class="md:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-all" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="md:hidden border-t border-gray-100 dark:border-gray-800 bg-white/95 dark:bg-gray-950/95 backdrop-blur-md">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ url('/') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-brand/5 hover:text-brand">Beranda</a>
                @auth
                    @if(Auth::user()->isPatient())
                        <a href="{{ route('booking.index') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-brand/5 hover:text-brand">Booking</a>
                        <a href="{{ route('status-dokter') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-brand/5 hover:text-brand">Status Dokter</a>
                        <a href="{{ route('patient.dashboard') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-brand/5 hover:text-brand">Antrean Saya</a>
                    @endif
                    <div class="border-t border-gray-100 dark:border-gray-800 mt-2 pt-2">
                        <p class="px-4 py-2 text-xs font-medium text-gray-400 uppercase tracking-widest">{{ Auth::user()->email }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 cursor-pointer">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-brand/5 hover:text-brand">Masuk</a>
                    <a href="{{ route('register') }}" class="block px-4 py-2.5 rounded-xl text-sm font-semibold bg-accent text-white hover:bg-accent-dark text-center">Daftar</a>
                @endguest

                {{-- Theme Toggle Mobile --}}
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', darkMode)" class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-lg text-sm hover:bg-white/10 cursor-pointer">
                    <span x-show="!darkMode" class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M3 12h2.25m-.386-6.364l1.591 1.591M12 18.75a6.75 6.75 0 110-13.5 6.75 6.75 0 010 13.5z"/></svg>
                        Mode Gelap
                    </span>
                    <span x-show="darkMode" class="flex items-center gap-2" style="display: none;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                        Mode Terang
                    </span>
                </button>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-900 dark:bg-black text-gray-400 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div>
                    <h3 class="text-white font-bold text-xl mb-4 tracking-tight">MyKlinik911</h3>
                    <p class="text-sm leading-relaxed opacity-80">Klinik terpercaya dengan pelayanan kesehatan profesional. Melayani dengan sepenuh hati untuk kesehatan Anda dan keluarga.</p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Jam Operasional</h3>
                    <ul class="text-sm space-y-2 opacity-80">
                        <li class="flex justify-between"><span>Senin - Jumat</span><span>08:00 - 17:00</span></li>
                        <li class="flex justify-between"><span>Sabtu</span><span>08:00 - 12:00</span></li>
                        <li class="flex justify-between text-red-400"><span>Minggu & Hari Libur</span><span>Tutup</span></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-4">Hubungi Kami</h3>
                    <ul class="text-sm space-y-3">
                        <li class="flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-full bg-brand/20 flex items-center justify-center text-brand group-hover:bg-brand group-hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            </div>
                            <span class="opacity-80 group-hover:opacity-100 transition-opacity">0812-3456-7890</span>
                        </li>
                        <li class="flex items-center gap-3 group">
                            <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            </div>
                            <span class="opacity-80 group-hover:opacity-100 transition-opacity">myklinik911@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-10 pt-8 text-center text-xs opacity-50">
                &copy; {{ date('Y') }} MyKlinik911. All rights reserved.
            </div>
        </div>
    </footer>

    {{-- Notification Bell Component --}}
    @auth
    @if(Auth::user()->isPatient())
    <script>
        function notificationBell() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                csrf: document.querySelector('meta[name="csrf-token"]')?.content,

                fetchNotifications() {
                    fetch('/notifications', {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    })
                    .then(r => r.json())
                    .then(data => {
                        this.notifications = data.notifications || [];
                        this.unreadCount = data.unread_count || 0;
                    })
                    .catch(() => {});
                },

                markAsRead(id) {
                    fetch(`/notifications/${id}/read`, {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' }
                    })
                    .then(() => {
                        const n = this.notifications.find(n => n.id === id);
                        if (n && !n.read) {
                            n.read = true;
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                        }
                    })
                    .catch(() => {});
                },

                markAllAsRead() {
                    fetch('/notifications/read-all', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' }
                    })
                    .then(() => {
                        this.notifications.forEach(n => n.read = true);
                        this.unreadCount = 0;
                    })
                    .catch(() => {});
                }
            };
        }
    </script>
    @endif
    @endauth

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
