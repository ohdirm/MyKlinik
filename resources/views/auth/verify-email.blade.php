<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email — MyKlinik911</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-brand to-brand-dark min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full text-center">
        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi Email Anda</h1>
        <p class="text-sm text-gray-500 mb-6">
            Kami telah mengirimkan kode verifikasi 6 digit ke email <strong class="text-gray-700">{{ Auth::user()->email }}</strong>.
            Silakan cek inbox (atau folder spam) Anda dan masukkan kode tersebut di bawah ini.
        </p>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <ul class="list-disc list-inside text-left">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('verification.verify-code') }}" class="mb-4">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1 text-left">Kode Verifikasi 6 Digit</label>
                <input type="text" name="code" id="code" class="input-base text-center text-xl tracking-widest font-bold" required maxlength="6" pattern="\d{6}" placeholder="000000">
            </div>
            <button type="submit" class="btn-primary w-full py-3">Verifikasi Kode</button>
        </form>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full py-3 text-brand font-medium hover:underline mb-3">📧 Belum terima kode? Kirim Ulang</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-outline w-full py-3">Logout</button>
        </form>
    </div>
</body>
</html>
