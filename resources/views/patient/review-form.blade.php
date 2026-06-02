@extends('layouts.app')
@section('title', 'Beri Review — MyKlinik911')
@section('content')
<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6 flex">
            <a href="{{ route('patient.dashboard') }}" class="inline-flex items-center gap-2 group text-sm font-medium transition-all" style="color: var(--ui-text-muted);">
                <div class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-800 flex items-center justify-center transition-all group-hover:border-brand group-hover:bg-brand group-hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </div>
                <span class="group-hover:text-brand">Kembali ke Dashboard</span>
            </a>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-gray-800">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Beri Review</h1>

            {{-- Booking info --}}
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 mb-6 text-sm space-y-1">
                <p><span class="text-gray-500 dark:text-gray-400">Kode Booking:</span> <strong class="text-brand">{{ $booking->booking_code }}</strong></p>
                <p><span class="text-gray-500 dark:text-gray-400">Dokter:</span> <strong class="dark:text-white">{{ $booking->doctor->name }}</strong> — <span class="dark:text-gray-300">{{ $booking->doctor->specialization_label }}</span></p>
                <p><span class="text-gray-500 dark:text-gray-400">Tanggal:</span> <span class="dark:text-gray-300">{{ $booking->exam_date->format('d/m/Y') }}</span></p>
            </div>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('review.store', $booking) }}">
                @csrf

                {{-- Tipe Review --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipe Review</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition has-[:checked]:bg-brand-light dark:has-[:checked]:bg-brand/20 has-[:checked]:border-brand">
                            <input type="radio" name="type" value="clinic" class="text-brand focus:ring-brand" {{ old('type', 'clinic') === 'clinic' ? 'checked' : '' }}>
                            <span class="text-sm font-medium dark:text-gray-200">🏥 Klinik</span>
                        </label>
                        <label class="flex items-center gap-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition has-[:checked]:bg-brand-light dark:has-[:checked]:bg-brand/20 has-[:checked]:border-brand">
                            <input type="radio" name="type" value="doctor" class="text-brand focus:ring-brand" {{ old('type') === 'doctor' ? 'checked' : '' }}>
                            <span class="text-sm font-medium dark:text-gray-200">🩺 Dokter ({{ $booking->doctor->name }})</span>
                        </label>
                    </div>
                </div>

                {{-- Rating --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                    <div class="flex gap-1" id="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="text-3xl transition-transform hover:scale-110 cursor-pointer star-btn {{ old('rating', 0) >= $i ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" data-star="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', 0) }}">
                </div>

                {{-- Komentar --}}
                <div class="mb-6" x-data="{ comment: '{{ old('comment', '') }}' }">
                    <div class="flex justify-between items-center mb-1">
                        <label for="comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Komentar</label>
                        <span class="text-[10px] uppercase font-bold text-gray-400" x-text="`${comment.length}/1000`"></span>
                    </div>
                    <textarea name="comment" id="comment" x-model="comment" rows="4" class="input-base" placeholder="Ceritakan pengalaman Anda (minimal 10 karakter)..." required minlength="10" maxlength="1000"></textarea>
                    <p class="mt-2 text-[10px] text-gray-500 dark:text-gray-400 italic">
                        💡 <strong>Tips:</strong> Berikan masukan yang sopan dan membangun. Review Anda akan dimoderasi oleh Admin sebelum ditampilkan.
                    </p>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1 py-3">Kirim Review</button>
                    <a href="{{ route('patient.dashboard') }}" class="btn-outline dark:text-gray-300 flex-1 py-3 text-center">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setRating(value) {
    document.getElementById('rating-input').value = value;
    document.querySelectorAll('.star-btn').forEach(function(btn) {
        const star = parseInt(btn.dataset.star);
        if (star <= value) {
            btn.classList.add('text-yellow-400');
            btn.classList.remove('text-gray-300', 'dark:text-gray-600');
        } else {
            btn.classList.remove('text-yellow-400');
            btn.classList.add('text-gray-300', 'dark:text-gray-600');
        }
    });
}
</script>
@endpush
@endsection
