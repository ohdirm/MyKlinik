@extends('layouts.app')

@section('title', 'Buat Booking - Klinik App')
@section('header_title', 'Buat Booking Baru')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="schedule_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Dokter & Jadwal Praktik</label>
            <select id="schedule_id" name="schedule_id" required
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                <option value="">-- Pilih Jadwal --</option>
                @foreach($schedules as $schedule)
                    <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                        {{ $schedule->doctor->name }} ({{ $schedule->doctor->specialization->name }}) - {{ ucfirst($schedule->day_of_week) }}, {{ $schedule->start_time->format('H:i') }}-{{ $schedule->end_time->format('H:i') }} di {{ $schedule->doctor->clinic->name }}
                    </option>
                @endforeach
            </select>
            @error('schedule_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Pastikan Anda memilih tanggal booking yang sesuai dengan hari praktik (Monday=Senin, dst).</p>
        </div>

        <div>
            <label for="booking_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking</label>
            <input type="date" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" required min="{{ date('Y-m-d') }}"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
            @error('booking_date')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Keluhan / Catatan (Opsional)</label>
            <textarea id="notes" name="notes" rows="4" placeholder="Jelaskan keluhan Anda dengan singkat..."
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">{{ old('notes') }}</textarea>
            @error('notes')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
                Buat Booking
            </button>
            <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
