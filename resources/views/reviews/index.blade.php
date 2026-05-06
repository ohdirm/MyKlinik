@extends('layouts.app')

@section('title', 'Review - Klinik App')
@section('header_title', 'Ulasan & Penilaian')

@section('content')
<div class="mb-8">
    <h3 class="text-2xl font-bold text-gray-800">Semua Ulasan Pasien</h3>
    <p class="text-sm text-gray-500 mt-1">Ulasan dan penilaian dari seluruh pasien yang telah selesai berkunjung.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($reviews as $review)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 flex flex-col hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h4 class="font-bold text-gray-900">{{ $review->booking->schedule->doctor->name }}</h4>
                <p class="text-xs text-gray-500">{{ $review->booking->schedule->doctor->specialization->name ?? '' }}</p>
                <p class="text-xs text-gray-400">{{ $review->booking->schedule->doctor->clinic->name }}</p>
            </div>
            <div class="flex items-center gap-0.5">
                @for($i = 1; $i <= 5; $i++)
                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
            </div>
        </div>
        <div class="flex-1">
            <p class="text-gray-600 text-sm italic">"{{ $review->comment ?? 'Tidak ada komentar.' }}"</p>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
            <span>Oleh: {{ $review->booking->user->name }}</span>
            <span>{{ $review->created_at->diffForHumans() }}</span>
        </div>
        @if(auth()->user()->isAdmin())
        <div class="mt-4 text-right">
            <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Hapus review ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
            </form>
        </div>
        @endif
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
        Belum ada ulasan.
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $reviews->links() }}
</div>
@endsection
