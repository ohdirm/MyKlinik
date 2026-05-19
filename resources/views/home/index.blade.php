@extends('layouts.app')

@section('title', 'MyKlinik911 — Klinik Terpercaya Untuk Kesehatan Anda')

@section('content')
{{-- Hero Section --}}
<section class="bg-gradient-to-br from-brand to-brand-dark text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-4 leading-tight">
            Selamat Datang di<br><span class="text-accent">MyKlinik911</span>
        </h1>
        <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
            Sistem pendaftaran online untuk konsultasi dengan dokter pilihan Anda. Mudah, cepat, dan terpercaya.
        </p>
        @auth
            <a href="{{ route('booking.index') }}" class="inline-block bg-accent hover:bg-accent-dark text-white px-8 py-3.5 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg shadow-accent/30">
                Buat Janji Sekarang
            </a>
        @else
            <div class="flex gap-3 justify-center flex-wrap">
                <a href="{{ route('register') }}" class="inline-block bg-accent hover:bg-accent-dark text-white px-8 py-3.5 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg shadow-accent/30">
                    Daftar & Buat Janji
                </a>
                <a href="{{ route('login') }}" class="inline-block bg-white/10 hover:bg-white/20 text-white px-8 py-3.5 rounded-xl font-semibold text-lg transition-all border border-white/20">
                    Sudah Punya Akun? Masuk
                </a>
            </div>
        @endauth
    </div>
</section>

{{-- About Section --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Tentang MyKlinik911</h2>
            <p class="text-gray-500 max-w-2xl mx-auto">Klinik kesehatan modern dengan pelayanan profesional dan sistem antrean digital yang memudahkan Anda.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-light text-brand rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <h3 class="font-semibold text-lg mb-2 text-gray-900">Dokter Berpengalaman</h3>
                <p class="text-sm text-gray-500">Tim dokter profesional dari berbagai spesialisasi siap melayani Anda</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-light text-brand rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-semibold text-lg mb-2 text-gray-900">Antrean Digital</h3>
                <p class="text-sm text-gray-500">Pantau antrean Anda secara real-time tanpa harus menunggu di klinik</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-brand-light text-brand rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="font-semibold text-lg mb-2 text-gray-900">Booking Online</h3>
                <p class="text-sm text-gray-500">Daftar akun, pilih dokter & jadwal, dan dapatkan nomor antrean secara instan</p>
            </div>
        </div>
    </div>
</section>

{{-- Doctor Cards --}}
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Dokter Kami</h2>
            <p class="text-gray-500">Tim dokter profesional siap melayani Anda</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($doctors as $doctor)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center gap-4 mb-4">
                    @if($doctor->photo)
                        <img src="{{ asset('storage/' . $doctor->photo) }}"
                             alt="{{ $doctor->name }}"
                             class="w-14 h-14 rounded-full object-cover shrink-0 border-2 border-brand/20">
                    @else
                        <div class="w-14 h-14 rounded-full bg-brand-light text-brand flex items-center justify-center font-bold text-lg shrink-0">
                            {{ $doctor->initials }}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $doctor->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $doctor->specialization_label }}</p>
                    </div>
                </div>
                @if($doctor->bio)
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $doctor->bio }}</p>
                @endif
                @php $status = $doctor->status; @endphp
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full {{ $status ? $status->status_badge_class : 'bg-green-100 text-green-800' }}">
                    <span class="w-2 h-2 rounded-full {{ $status && $status->current_status === 'AVAILABLE' ? 'bg-green-500' : ($status && $status->current_status === 'IN_EXAMINATION' ? 'bg-yellow-500' : ($status && $status->current_status === 'UNAVAILABLE' ? 'bg-red-500' : 'bg-blue-500')) }}"></span>
                    {{ $status ? $status->status_label : 'Tersedia' }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Reviews Section --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Apa Kata Pasien Kami</h2>
            <p class="text-gray-500">Ulasan dari pasien yang telah menggunakan layanan MyKlinik911</p>
        </div>
        @if($reviews->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($reviews as $review)
                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 bg-brand-light text-brand rounded-full flex items-center justify-center font-bold text-sm shrink-0">
                            {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 text-sm">{{ $review->user->name ?? 'Anonim' }}</p>
                            <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="text-yellow-400 text-lg mb-2">{{ $review->rating_stars }}</div>
                    @if($review->type === 'doctor' && $review->doctor)
                        <p class="text-xs text-brand font-semibold mb-1">🩺 {{ $review->doctor->name }}</p>
                    @else
                        <p class="text-xs text-accent font-semibold mb-1">🏥 Review Klinik</p>
                    @endif
                    <p class="text-sm text-gray-600 line-clamp-3">{{ $review->comment }}</p>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-gray-400">
                <p class="text-lg mb-1">Belum ada review</p>
                <p class="text-sm">Review dari pasien akan tampil di sini</p>
            </div>
        @endif
    </div>
</section>

{{-- CTA Section --}}
<section class="py-16 bg-gradient-to-r from-brand to-brand-dark text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Siap Untuk Berkonsultasi?</h2>
        <p class="text-blue-100 mb-8">Daftar sekarang dan buat janji temu dengan dokter pilihan Anda</p>
        @auth
            <a href="{{ route('booking.index') }}" class="inline-block bg-accent hover:bg-accent-dark text-white px-8 py-3.5 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                Buat Janji Sekarang
            </a>
        @else
            <a href="{{ route('register') }}" class="inline-block bg-accent hover:bg-accent-dark text-white px-8 py-3.5 rounded-xl font-semibold text-lg transition-all transform hover:scale-105 shadow-lg">
                Daftar Gratis
            </a>
        @endauth
    </div>
</section>
@endsection
