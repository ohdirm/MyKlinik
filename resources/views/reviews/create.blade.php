@extends('layouts.app')

@section('title', 'Tulis Review - Klinik App')
@section('header_title', 'Tulis Ulasan Baru')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
    <div class="mb-6 pb-6 border-b border-gray-100">
        <h4 class="text-lg font-bold text-gray-800 mb-2">Penilaian untuk:</h4>
        <div class="text-gray-600">
            <span class="font-medium text-gray-900">{{ $booking->schedule->doctor->name }}</span>
            di {{ $booking->schedule->doctor->clinic->name }}<br>
            Tanggal Kunjungan: {{ $booking->booking_date->format('d M Y') }}
        </div>
    </div>

    <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Beri Rating (1-5 Bintang)</label>
            <div class="flex gap-4">
                @for($i = 1; $i <= 5; $i++)
                <label class="cursor-pointer">
                    <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only" required>
                    <div class="w-12 h-12 flex items-center justify-center rounded-xl border-2 border-gray-200 peer-checked:border-yellow-400 peer-checked:bg-yellow-50 text-gray-400 peer-checked:text-yellow-500 hover:bg-gray-50 transition-all">
                        <span class="text-lg font-bold">{{ $i }}</span>
                    </div>
                </label>
                @endfor
            </div>
            @error('rating')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Komentar / Ulasan (Opsional)</label>
            <textarea id="comment" name="comment" rows="4" placeholder="Bagaimana pengalaman Anda..."
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">{{ old('comment') }}</textarea>
            @error('comment')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
                Kirim Ulasan
            </button>
            <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
