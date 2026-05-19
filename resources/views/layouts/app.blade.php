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
</head>
<body class="bg-gray-50 min-h-screen font-sans flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-brand text-white shadow-lg sticky top-0 z-40" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span class="font-bold text-xl tracking-tight">MyKlinik911</span>
                </a>.

                {{-- Desktop nav --}}
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-white/10 transition {{ request()->is('/') ? 'bg-white/15' : '' }}">Beranda</a>
                    @auth
                        @if(Auth::user()->isPatient())
                            <a href="{{ route('booking.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-white/10 transition {{ request()->is('booking') ? 'bg-white/15' : '' }}">Booking</a>
                            <a href="{{ route('status-dokter') }}" class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-white/10 transition {{ request()->is('status-dokter') ? 'bg-white/15' : '' }}">Status Dokter</a>
                            <a href="{{ route('patient.dashboard') }}" class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-white/10 transition {{ request()->is('antrean-saya') ? 'bg-white/15' : '' }}">Antrean Saya</a>
                        @endif
                    @endauth

                    {{-- Auth buttons --}}
                    @guest
                        <a href="{{ route('login') }}" class="ml-2 px-4 py-2 rounded-lg text-sm font-medium bg-white/10 hover:bg-white/20 transition">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg text-sm font-medium bg-accent hover:bg-accent-dark transition">Daftar</a>
                    @else
                        <div class="relative ml-2" x-data="{ dropdown: false }">
                            <button @click="dropdown = !dropdown" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition cursor-pointer">
                                <div class="w-7 h-7 bg-white/20 rounded-full flex items-center justify-center text-xs font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                                <span class="text-sm">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                            </button>
                            <div x-show="dropdown" @click.away="dropdown = false" x-transition class="absolute right-0 mt-1 w-48 bg-white rounded-xl shadow-lg py-2 z-50">
                                @if(Auth::user()->isPatient())
                                    <a href="{{ route('patient.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Dashboard</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50 cursor-pointer">Logout</button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                {{-- Mobile hamburger --}}
                <button @click="open = !open" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-transition class="md:hidden border-t border-white/10">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ url('/') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Beranda</a>
                @auth
                    @if(Auth::user()->isPatient())
                        <a href="{{ route('booking.index') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Booking</a>
                        <a href="{{ route('status-dokter') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Status Dokter</a>
                        <a href="{{ route('patient.dashboard') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Antrean Saya</a>
                    @endif
                    <div class="border-t border-white/10 mt-2 pt-2">
                        <p class="px-3 py-1 text-xs text-white/60">{{ Auth::user()->email }}</p>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-300 hover:bg-white/10 cursor-pointer">Logout</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded-lg text-sm hover:bg-white/10">Masuk</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-lg text-sm bg-accent hover:bg-accent-dark">Daftar</a>
                @endguest
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="flex-1">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-800 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-white font-bold text-lg mb-3">MyKlinik911</h3>
                    <p class="text-sm leading-relaxed">Klinik terpercaya dengan pelayanan kesehatan profesional. Melayani dengan sepenuh hati untuk kesehatan Anda dan keluarga.</p>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3">Jam Operasional</h3>
                    <ul class="text-sm space-y-1">
                        <li>Senin - Jumat: 08:00 - 17:00</li>
                        <li>Sabtu: 08:00 - 12:00</li>
                        <li>Minggu & Hari Libur: Tutup</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-white font-semibold mb-3">Hubungi Kami</h3>
                    <ul class="text-sm space-y-2">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                            <span>0812-3456-7890</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-accent" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                            <span>myklinik911@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-6 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} MyKlinik911. All rights reserved.
            </div>
        </div>
    </footer>

    {{-- Alpine.js CDN --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>
</html>
