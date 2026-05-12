@extends('layouts.app')
@section('title', 'Antrean Saya — MyKlinik911')
@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard Pasien</h1>

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Booking Aktif --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="font-semibold text-lg text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    Booking Aktif Saya
                </h2>
                @forelse($activeBookings as $b)
                    <div class="border border-gray-100 rounded-xl p-4 mb-3 last:mb-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-mono font-bold text-brand text-lg">{{ $b->booking_code }}</span>
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $b->status_badge_class }}">{{ $b->status }}</span>
                        </div>
                        <div class="text-sm text-gray-600 space-y-1">
                            <p>🩺 <strong>{{ $b->doctor->name ?? '-' }}</strong> — {{ $b->doctor->specialization_label ?? '' }}</p>
                            <p>📅 {{ $b->exam_date->format('d/m/Y') }} — {{ $b->schedule->day_name ?? '' }}, {{ $b->schedule->time_range ?? '' }}</p>
                            <div class="flex items-center gap-4 mt-2">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">Antrean</p>
                                    <p class="text-2xl font-bold text-brand">#{{ $b->queue_number }}</p>
                                </div>
                                @if($b->estimated_time)
                                <div class="border-l border-gray-200 pl-4">
                                    <p class="text-xs text-gray-500 uppercase tracking-wider">Estimasi Dilayani</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $b->estimated_time }}</p>
                                </div>
                                @endif
                            </div>
                @empty
                    <div class="text-center py-8 text-gray-400">
                        <p class="mb-2">Belum ada booking aktif</p>
                        <a href="{{ route('booking.index') }}" class="btn-primary text-sm px-4 py-2">Buat Janji Sekarang</a>
                    </div>
                @endforelse
            </div>

            {{-- Antrean Hari Ini (Publik) --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="font-semibold text-lg text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                    Antrean Hari Ini
                </h2>
                @if($todayQueue->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                                <tr>
                                    <th class="px-3 py-2 text-left">No.</th>
                                    <th class="px-3 py-2 text-left">Dokter</th>
                                    <th class="px-3 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($todayQueue as $q)
                                <tr>
                                    <td class="px-3 py-2 font-bold">{{ $q['queue_number'] }}</td>
                                    <td class="px-3 py-2">{{ $q['doctor_name'] }}</td>
                                    <td class="px-3 py-2">
                                        @if($q['status'] === 'CONFIRMED')
                                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-800">Menunggu</span>
                                        @else
                                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-800">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center py-6 text-gray-400">Tidak ada antrean hari ini</p>
                @endif

                {{-- Status Dokter Real-time --}}
                <h3 class="font-semibold text-gray-900 mt-6 mb-3">Status Dokter</h3>
                <div class="space-y-2">
                    @foreach($doctorStatuses as $ds)
                        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-3 py-2">
                            <span class="text-sm font-medium">{{ $ds->doctor->name ?? '-' }}</span>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $ds->status_badge_class }}">
                                {{ $ds->status_label }}
                                @if($ds->current_status === 'IN_EXAMINATION' && $ds->current_queue_number)
                                    — No. {{ $ds->current_queue_number }}
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Riwayat Booking Selesai --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 mt-6">
            <h2 class="font-semibold text-lg text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Riwayat Kunjungan
            </h2>
            @if($completedBookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                            <tr>
                                <th class="px-4 py-2 text-left">Kode</th>
                                <th class="px-4 py-2 text-left">Dokter</th>
                                <th class="px-4 py-2 text-left">Tanggal</th>
                                <th class="px-4 py-2 text-left">Status</th>
                                <th class="px-4 py-2 text-left">Review</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($completedBookings as $cb)
                            <tr>
                                <td class="px-4 py-2 font-mono font-semibold text-brand">{{ $cb->booking_code }}</td>
                                <td class="px-4 py-2">{{ $cb->doctor->name ?? '-' }}</td>
                                <td class="px-4 py-2">{{ $cb->exam_date->format('d/m/Y') }}</td>
                                <td class="px-4 py-2"><span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-800">Selesai</span></td>
                                <td class="px-4 py-2">
                                    @if(in_array($cb->id, $reviewedBookingIds))
                                        <span class="text-xs text-gray-400">✓ Sudah review</span>
                                    @else
                                        <a href="{{ route('review.create', $cb) }}" class="text-xs bg-accent hover:bg-accent-dark text-white px-3 py-1.5 rounded-lg transition inline-block">Beri Review</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center py-6 text-gray-400">Belum ada riwayat kunjungan</p>
            @endif
        </div>

    </div>
</div>
@endsection
