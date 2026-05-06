@extends('layouts.app')

@section('title', 'Ketersediaan Dokter - Klinik App')
@section('header_title', 'Ketersediaan Dokter')

@section('content')
<div class="mb-8">
    <h3 class="text-2xl font-bold text-gray-800">Status Ketersediaan Dokter</h3>
    <p class="text-sm text-gray-500 mt-1">Lihat jadwal dan ketersediaan dokter pada tanggal tertentu sebelum melakukan booking.</p>
</div>

<!-- Date Picker -->
<div class="mb-8 bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <form method="GET" action="{{ route('availability.index') }}" class="flex items-end gap-4">
        <div class="flex-1 max-w-xs">
            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Pilih Tanggal</label>
            <input type="date" id="date" name="date" value="{{ $selectedDate }}" min="{{ date('Y-m-d') }}"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
        </div>
        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all">
            Cek Ketersediaan
        </button>
    </form>
    <p class="text-sm text-gray-500 mt-3">
        Menampilkan jadwal untuk hari: <span class="font-semibold capitalize text-indigo-600">{{ $dayOfWeek }}</span>, 
        tanggal <span class="font-semibold text-indigo-600">{{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</span>
    </p>
</div>

@if($schedules->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 text-center text-gray-500">
        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="font-medium text-gray-700 text-lg">Tidak ada jadwal praktik pada hari ini</p>
        <p class="text-sm mt-1">Silakan pilih tanggal lain untuk melihat ketersediaan dokter.</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($schedules as $schedule)
        <div class="bg-white rounded-2xl shadow-sm border {{ $schedule->is_full ? 'border-red-200' : 'border-green-200' }} overflow-hidden hover:shadow-md transition-shadow">
            <!-- Header Status -->
            <div class="px-6 py-3 {{ $schedule->is_full ? 'bg-red-50' : 'bg-green-50' }}">
                <div class="flex items-center justify-between">
                    @if($schedule->is_full)
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">PENUH</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">TERSEDIA</span>
                    @endif
                    <span class="text-xs font-medium {{ $schedule->is_full ? 'text-red-600' : 'text-green-600' }}">
                        Sisa {{ $schedule->available_slots }}/{{ $schedule->max_patients }} slot
                    </span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6">
                <h4 class="font-bold text-gray-900 text-lg">{{ $schedule->doctor->name }}</h4>
                <p class="text-sm text-indigo-600 font-medium">{{ $schedule->doctor->specialization->name }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $schedule->doctor->clinic->name }}</p>

                <div class="mt-4 space-y-2 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $schedule->booked_count }} dari {{ $schedule->max_patients }} pasien terdaftar
                    </div>
                </div>

                <!-- Progress bar -->
                <div class="mt-4">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php $pct = $schedule->max_patients > 0 ? round(($schedule->booked_count / $schedule->max_patients) * 100) : 0; @endphp
                        <div class="h-2 rounded-full {{ $pct >= 100 ? 'bg-red-500' : ($pct >= 70 ? 'bg-yellow-500' : 'bg-green-500') }}" style="width: {{ min($pct, 100) }}%"></div>
                    </div>
                </div>

                @if(!$schedule->is_full)
                <div class="mt-5">
                    <a href="{{ route('bookings.create', ['schedule_id' => $schedule->id, 'date' => $selectedDate]) }}" 
                       class="block text-center w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all text-sm">
                        Booking Sekarang
                    </a>
                </div>
                @else
                <div class="mt-5">
                    <button disabled class="block w-full px-4 py-2 bg-gray-300 text-gray-500 font-medium rounded-lg cursor-not-allowed text-sm text-center">
                        Kuota Penuh
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
