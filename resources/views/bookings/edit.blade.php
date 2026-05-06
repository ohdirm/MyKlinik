@extends('layouts.app')

@section('title', 'Detail Booking - Klinik App')
@section('header_title', 'Detail & Edit Booking')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
    <div class="mb-6 pb-6 border-b border-gray-100">
        <h4 class="text-lg font-bold text-gray-800 mb-4">Informasi Booking #BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</h4>
        <div class="grid grid-cols-2 gap-y-4 text-sm">
            <div>
                <span class="block text-gray-500 mb-1">Pasien</span>
                <span class="font-medium text-gray-900">{{ $booking->user->name }}</span>
            </div>
            <div>
                <span class="block text-gray-500 mb-1">Dokter</span>
                <span class="font-medium text-gray-900">{{ $booking->schedule->doctor->name }}</span>
            </div>
            <div>
                <span class="block text-gray-500 mb-1">Tanggal Booking</span>
                <span class="font-medium text-gray-900">{{ $booking->booking_date->format('d F Y') }}</span>
            </div>
            <div>
                <span class="block text-gray-500 mb-1">Waktu Praktik & No. Antrian</span>
                <span class="font-medium text-gray-900">{{ $booking->schedule->start_time->format('H:i') }} - {{ $booking->schedule->end_time->format('H:i') }} (Antrian: {{ $booking->queue_number }})</span>
            </div>
        </div>
    </div>

    <form action="{{ route('bookings.update', $booking) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        @if(auth()->user()->isAdmin())
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status Booking</label>
            <select id="status" name="status" required
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="in_progress" {{ $booking->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            @error('status')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>
        @else
            <div>
                <span class="block text-sm font-medium text-gray-700 mb-1">Status Booking</span>
                @if($booking->status == 'pending')
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                @elseif($booking->status == 'confirmed')
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Confirmed</span>
                @elseif($booking->status == 'in_progress')
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">In Progress</span>
                @elseif($booking->status == 'completed')
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">Completed</span>
                @else
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">Cancelled</span>
                @endif
            </div>
        @endif

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Keluhan / Catatan</label>
            <textarea id="notes" name="notes" rows="4" {{ !auth()->user()->isAdmin() && in_array($booking->status, ['completed', 'cancelled']) ? 'readonly' : '' }}
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">{{ old('notes', $booking->notes) }}</textarea>
            @error('notes')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4">
            @if(auth()->user()->isAdmin() || !in_array($booking->status, ['completed', 'cancelled']))
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
                    Simpan Perubahan
                </button>
            @endif
            
            @if(!auth()->user()->isAdmin() && in_array($booking->status, ['pending', 'confirmed']))
                <button type="submit" name="cancel_booking" value="1" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-red-100" onclick="return confirm('Yakin ingin membatalkan booking ini?')">
                    Batalkan Booking
                </button>
            @endif

            <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-all">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection
