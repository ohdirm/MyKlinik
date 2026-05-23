<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — MyKlinik911</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gradient-to-br from-brand to-brand-dark min-h-screen flex items-center justify-center p-4 font-sans">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full">
        <div class="text-center mb-6">
            <a href="{{ url('/') }}" class="inline-block mb-3 hover:opacity-90 transition-opacity">
                <img src="{{ asset('assets/logo_app.png') }}" alt="MyKlinik911 Logo" class="h-20 w-auto mx-auto">
            </a>
            <h1 class="text-2xl font-bold text-gray-900">MyKlinik911</h1>
            <p class="text-sm text-gray-500">Masuk ke panel administrasi</p>
        </div>
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif
        <form method="POST" action="{{ url('/admin/login') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" class="input-base" value="{{ old('email') }}" required autofocus>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" class="input-base" required>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 text-brand focus:ring-brand">
                    <label for="remember" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>
                <button type="submit" class="btn-primary w-full py-3">Masuk</button>
            </div>
        </form>
    </div>
</body>
</html>
