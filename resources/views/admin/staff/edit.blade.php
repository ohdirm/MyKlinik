@extends('layouts.admin')

@section('title', 'Edit Staff — MyKlinik911')
@section('page_title', 'Edit Staff')
@section('page_subtitle', 'Perbarui informasi akun pekerja')

@section('content')
<div class="max-w-2xl mx-auto animate-in fade-in slide-in-from-bottom-4 duration-500">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.staff.index') }}" class="inline-flex items-center gap-2 group text-sm font-medium text-gray-500 dark:text-gray-400">
            <div class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-800 flex items-center justify-center group-hover:border-brand group-hover:bg-brand group-hover:text-white transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </div>
            <span class="group-hover:text-brand">Kembali ke Daftar Staff</span>
        </a>
    </div>

    <div class="bg-white dark:bg-[#1c2622] rounded-[2.5rem] shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden">
        {{-- Header Section --}}
        <div class="px-10 py-8 bg-gray-50/50 dark:bg-gray-800/10 border-b border-gray-100 dark:border-gray-800">
            <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Perbarui Akun: {{ $staff->name }}</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kosongkan password jika tidak ingin mengubahnya.</p>
        </div>

        <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" class="p-10 space-y-6">
            @csrf
            @method('PUT')
            
            {{-- Name --}}
            <div class="space-y-2">
                <label for="name" class="text-sm font-bold text-gray-800 dark:text-gray-200 px-1">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $staff->name) }}" required
                       class="w-full px-5 py-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-gray-100 dark:border-gray-800 focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all font-medium text-gray-700 dark:text-gray-300"
                       placeholder="Masukkan nama lengkap staff">
                @error('name') <p class="text-xs text-rose-500 font-bold px-1 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div class="space-y-2">
                <label for="email" class="text-sm font-bold text-gray-800 dark:text-gray-200 px-1">Alamat Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $staff->email) }}" required
                       class="w-full px-5 py-4 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border-gray-100 dark:border-gray-800 focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all font-medium text-gray-700 dark:text-gray-300"
                       placeholder="staff@myklinik.id">
                @error('email') <p class="text-xs text-rose-500 font-bold px-1 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-brand/5 dark:bg-brand/5 rounded-3xl p-6 border border-brand/10">
                <h4 class="text-xs font-black text-brand uppercase tracking-widest mb-4">Ganti Password (Opsional)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Password --}}
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-bold text-gray-800 dark:text-gray-200 px-1">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-800 focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all font-medium text-gray-700 dark:text-gray-300"
                            placeholder="••••••••">
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-sm font-bold text-gray-800 dark:text-gray-200 px-1">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-5 py-4 rounded-2xl bg-white dark:bg-gray-800 border-gray-100 dark:border-gray-800 focus:ring-4 focus:ring-brand/10 focus:border-brand transition-all font-medium text-gray-700 dark:text-gray-300"
                            placeholder="••••••••">
                    </div>
                </div>
                @error('password') <p class="text-xs text-rose-500 font-bold px-1 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-brand hover:bg-brand-dark text-white font-extrabold py-5 rounded-[1.5rem] transition-all shadow-xl shadow-brand/20 active:scale-[0.98] cursor-pointer">
                    Simpan Perubahan Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
