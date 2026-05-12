@extends('layouts.app')
@section('title', 'Beri Review — MyKlinik911')
@section('content')
<div class="py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('patient.dashboard') }}" class="text-sm text-brand hover:underline mb-4 inline-block">← Kembali ke Dashboard</a>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h1 class="text-xl font-bold text-gray-900 mb-2">Beri Review</h1>

            {{-- Booking info --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6 text-sm space-y-1">
                <p><span class="text-gray-500">Kode Booking:</span> <strong class="text-brand">{{ $booking->booking_code }}</strong></p>
                <p><span class="text-gray-500">Dokter:</span> <strong>{{ $booking->doctor->name }}</strong> — {{ $booking->doctor->specialization_label }}</p>
                <p><span class="text-gray-500">Tanggal:</span> {{ $booking->exam_date->format('d/m/Y') }}</p>
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Review</label>
                    <div class="flex gap-3">
                        <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:bg-gray-100 transition has-[:checked]:bg-brand-light has-[:checked]:border-brand">
                            <input type="radio" name="type" value="clinic" class="text-brand focus:ring-brand" {{ old('type', 'clinic') === 'clinic' ? 'checked' : '' }}>
                            <span class="text-sm font-medium">🏥 Klinik</span>
                        </label>
                        <label class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 cursor-pointer hover:bg-gray-100 transition has-[:checked]:bg-brand-light has-[:checked]:border-brand">
                            <input type="radio" name="type" value="doctor" class="text-brand focus:ring-brand" {{ old('type') === 'doctor' ? 'checked' : '' }}>
                            <span class="text-sm font-medium">🩺 Dokter ({{ $booking->doctor->name }})</span>
                        </label>
                    </div>
                </div>

                {{-- Rating --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex gap-1" id="star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="text-3xl transition-transform hover:scale-110 cursor-pointer star-btn {{ old('rating', 0) >= $i ? 'text-yellow-400' : 'text-gray-300' }}" data-star="{{ $i }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', 0) }}">
                </div>

                {{-- Komentar --}}
                <div class="mb-6">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Komentar</label>
                    <textarea name="comment" id="comment" rows="4" class="input-base" placeholder="Ceritakan pengalaman Anda (minimal 10 karakter)..." required minlength="10">{{ old('comment') }}</textarea>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="btn-primary flex-1 py-3">Kirim Review</button>
                    <a href="{{ route('patient.dashboard') }}" class="btn-outline flex-1 py-3 text-center">Batal</a>
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
        btn.classList.toggle('text-yellow-400', star <= value);
        btn.classList.toggle('text-gray-300', star > value);
    });
}
</script>
@endpush
@endsection
