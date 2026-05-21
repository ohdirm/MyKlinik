<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — MyKlinik911</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-brand to-brand-dark min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
        <div class="text-center mb-6">
            <a href="{{ route('home') }}" class="inline-block">
                <div class="w-16 h-16 bg-brand-light text-brand rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Akun Baru</h1>
            <p class="text-sm text-gray-500">Buat akun untuk mulai booking dokter</p>
        </div>

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            {{-- Honeypot field for bot protection --}}
            <div style="display: none;">
                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
            </div>
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="input-base" value="{{ old('name') }}" placeholder="Masukkan Nama" required autofocus>
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" class="input-base" value="{{ old('email') }}" placeholder="example.911@gmail.com" required>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" class="input-base" placeholder="Masukkan Password" required minlength="8">
                    <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter</p>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="input-base" placeholder="Masukkan Ulang Password" required>
                </div>
                <div>
                    <label class="flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" name="terms" id="terms" class="mt-1 rounded border-gray-300 text-brand shadow-sm focus:border-brand-light focus:ring focus:ring-brand-light focus:ring-opacity-50" required>
                        <span class="text-sm text-gray-600">
                            Saya menyetujui <a href="{{ route('terms') }}" target="_blank" class="text-brand font-medium hover:underline">Syarat & Ketentuan</a> serta <a href="{{ route('privacy') }}" target="_blank" class="text-brand font-medium hover:underline">Kebijakan Privasi</a> mengenai data pribadi.
                        </span>
                    </label>
                </div>
                <button type="submit" class="btn-primary w-full py-3">Daftar</button>
            </div>
        </form>

        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-3 text-sm text-blue-700">
            <strong>📧 Verifikasi Email:</strong> Setelah mendaftar, Anda akan menerima email verifikasi. Silakan klik link di email untuk mengaktifkan akun.
        </div>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-500">Sudah punya akun? <a href="{{ route('login') }}" class="text-brand font-semibold hover:underline">Masuk</a></p>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-600">← Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
