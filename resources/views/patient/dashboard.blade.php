@extends('layouts.app')
@section('title', 'Antrean Saya — MyKlinik911')
@section('content')
<div class="py-10 bg-gray-50/50 dark:bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-8 flex">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3 group text-sm font-semibold transition-all text-gray-500 dark:text-gray-400">
                <div class="w-10 h-10 rounded-full border border-gray-200 dark:border-gray-800 flex items-center justify-center transition-all group-hover:border-brand group-hover:bg-brand group-hover:text-white shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </div>
                <span class="group-hover:text-brand transition-colors">Kembali ke Beranda</span>
            </a>
        </div>

        <div class="mb-10 lg:flex lg:items-center lg:justify-between gap-6">
            <div>
                <h1 class="text-3xl lg:text-4xl font-black text-gray-900 dark:text-white tracking-tight">Dashboard Pasien</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2 text-lg">Kelola pendaftaran dan pantau antrean Anda secara real-time.</p>
            </div>
            <div class="mt-6 lg:mt-0">
                <a href="{{ route('booking.index') }}" class="btn-primary flex items-center justify-center gap-2 px-8 py-3.5 shadow-xl shadow-brand/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Buat Janji Baru
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-6 py-4 rounded-2xl text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
                <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            {{-- Main Content Area --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- Booking Aktif Section --}}
                <div class="bg-white dark:bg-[#1c2622] rounded-[2.5rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-[#e2efe7] dark:border-[#283731] overflow-hidden">
                    <div class="p-8 pb-0">
                        <h2 class="font-bold text-xl text-gray-900 dark:text-white flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-brand/10 text-brand flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            </div>
                            Booking Aktif Saya
                        </h2>
                    </div>

                    <div class="p-8">
                        @forelse($activeBookings as $b)
                            <div class="bg-[#F6FBF8] dark:bg-[#141b18]/50 border border-[#e2efe7] dark:border-[#283731]/50 rounded-3xl p-8 transition-all hover:bg-brand/5 dark:hover:bg-brand/5 group mb-6 last:mb-0">
                                <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-1">Kode Reservasi</p>
                                        <span class="font-mono font-black text-brand-dark dark:text-brand text-2xl tracking-tighter">{{ $b->booking_code }}</span>
                                    </div>
                                    <span class="text-[10px] uppercase tracking-widest font-black px-4 py-1.5 rounded-full shadow-sm {{ $b->status_badge_class }}">{{ $b->status }}</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-4">
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-[#141b18] shadow-sm flex items-center justify-center text-2xl shrink-0 border border-[#e2efe7] dark:border-[#283731]">🩺</div>
                                            <div>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Dokter Pemeriksa</p>
                                                <p class="font-bold text-gray-900 dark:text-white text-lg leading-tight">{{ $b->doctor->name ?? '-' }}</p>
                                                <p class="text-sm text-brand-dark dark:text-brand font-medium">{{ $b->doctor->specialization_label ?? '' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-white dark:bg-[#141b18] shadow-sm flex items-center justify-center text-2xl shrink-0 border border-[#e2efe7] dark:border-[#283731]">📅</div>
                                            <div>
                                                <p class="text-xs text-gray-400 dark:text-gray-500 font-bold uppercase tracking-wider mb-1">Jadwal & Waktu</p>
                                                <p class="font-bold text-gray-900 dark:text-white text-lg leading-tight">{{ $b->exam_date->format('d/m/Y') }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $b->schedule->day_name ?? '' }}, {{ $b->schedule->time_range ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white dark:bg-[#141b18]/60 rounded-2xl p-6 flex items-center justify-around text-center shadow-inner border border-[#e2efe7] dark:border-[#283731]">
                                        <div>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-widest font-black mb-1">No. Antrean</p>
                                            <p class="text-5xl font-black text-brand-dark dark:text-brand">#{{ $b->queue_number }}</p>
                                        </div>
                                        @if($b->estimated_time)
                                        <div class="w-px h-12 bg-gray-100 dark:bg-gray-800"></div>
                                        <div>
                                            <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-widest font-black mb-1">Estimasi Jam</p>
                                            <p class="text-4xl font-black text-brand-dark dark:text-brand">{{ $b->estimated_time }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20 bg-[#F6FBF8] dark:bg-[#1c2622]/10 rounded-[2rem] border-2 border-dashed border-[#e2efe7] dark:border-[#283731]">
                                <div class="w-20 h-20 bg-white dark:bg-[#141b18] rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl border border-[#e2efe7] dark:border-[#283731]">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Antrean Aktif</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-xs mx-auto">Silakan pilih jadwal dokter dan buat janji pemeriksaan Anda hari ini.</p>
                                <a href="{{ route('booking.index') }}" class="btn-primary inline-flex items-center gap-3 px-8 py-3 rounded-2xl">
                                    Mulai Pendaftaran
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Riwayat Kunjungan Section --}}
                <div class="bg-white dark:bg-[#1c2622] rounded-[2.5rem] shadow-xl shadow-gray-200/50 dark:shadow-none border border-[#e2efe7] dark:border-[#283731] p-8 text-white">
                    <h2 class="font-bold text-xl text-gray-900 dark:text-white mb-8 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-[#F6FBF8] dark:bg-[#141b18] text-[#6B9080] flex items-center justify-center border border-[#e2efe7] dark:border-[#283731]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        Riwayat Kunjungan
                    </h2>
                    @if($completedBookings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-black text-[#6B9080] dark:text-[#A8D5BA] uppercase tracking-[0.2em] border-b border-[#e2efe7] dark:border-[#283731]">
                                        <th class="px-4 py-4">Kode</th>
                                        <th class="px-4 py-4">Dokter Pemeriksa</th>
                                        <th class="px-4 py-4">Tanggal</th>
                                        <th class="px-4 py-4 text-right">Review</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-[#e2efe7] dark:divide-[#283731]">
                                    @foreach($completedBookings as $cb)
                                    <tr class="group text-gray-900 dark:text-white">
                                        <td class="px-4 py-6 font-mono font-bold text-gray-400 group-hover:text-brand transition-colors">{{ $cb->booking_code }}</td>
                                        <td class="px-4 py-6">
                                            <p class="font-bold text-gray-900 dark:text-white leading-tight">{{ $cb->doctor->name ?? '-' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $cb->doctor->specialization_label ?? '' }}</p>
                                        </td>
                                        <td class="px-4 py-6 font-medium text-gray-600 dark:text-gray-400">{{ $cb->exam_date->format('d M Y') }}</td>
                                        <td class="px-4 py-6 text-right">
                                            @if(in_array($cb->id, $reviewedBookingIds))
                                                <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-500 bg-green-50 dark:bg-green-900/20 px-3 py-1.5 rounded-full">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                    Sudah Review
                                                </span>
                                            @else
                                                <a href="{{ route('review.create', $cb) }}" class="inline-flex items-center gap-2 text-xs font-bold bg-brand/10 hover:bg-brand text-brand hover:text-white px-4 py-2 rounded-xl transition-all shadow-sm">
                                                    Review
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-12 text-center text-[#6B9080] dark:text-[#A8D5BA] bg-[#F6FBF8] dark:bg-[#1c2622]/20 rounded-2xl border border-dashed border-[#e2efe7] dark:border-[#283731]">
                            Belum ada riwayat kunjungan yang tercatat.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Live Today Queue --}}
                <div class="bg-gradient-to-br from-brand-dark to-accent-dark dark:from-[#1c2622] dark:to-[#141b18] dark:border dark:border-[#283731] rounded-[2.5rem] p-8 text-white shadow-2xl shadow-brand/20 dark:shadow-none sticky top-24">
                    <h2 class="font-bold text-xl mb-6 flex items-center gap-3 text-white">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                        </div>
                        Antrean Hari Ini
                    </h2>
                    
                    <div class="space-y-3 mb-8">
                        @forelse($todayQueue->take(6) as $q)
                        <div class="flex items-center justify-between p-4 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/10 transition-transform hover:scale-[1.02]">
                            <div class="flex items-center gap-3">
                                <span class="w-10 h-10 rounded-full bg-white text-brand-dark flex items-center justify-center font-black">#{{ $q['queue_number'] }}</span>
                                <div class="text-sm font-bold truncate max-w-[120px]">{{ $q['doctor_name'] }}</div>
                            </div>
                            <span class="text-[10px] font-black px-2 py-1 rounded-full {{ $q['status'] === 'CONFIRMED' ? 'bg-white/20 text-white' : 'bg-green-400 text-green-900' }}">
                                {{ $q['status'] === 'CONFIRMED' ? 'ANTRE' : 'OK' }}
                            </span>
                        </div>
                        @empty
                        <p class="text-center py-6 text-white/50 italic text-sm">Tidak ada antrean hari ini.</p>
                        @endforelse
                    </div>

                    {{-- Live Status Section --}}
                    <div class="pt-6 border-t border-white/10">
                        <h3 class="text-xs font-black uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-green-400 animate-ping"></span>
                            Status Dokter (Live)
                        </h3>
                        <div class="space-y-2">
                            @foreach($doctorStatuses as $ds)
                                <div class="flex items-center justify-between bg-white/5 rounded-xl px-4 py-3 hover:bg-white/10 transition-colors">
                                    <span class="text-xs font-bold truncate max-w-[140px]">{{ $ds->doctor->name ?? '-' }}</span>
                                    <span class="text-[9px] font-black px-2 py-0.5 rounded-full {{ $ds->status_badge_class }}">
                                        {{ $ds->status_label }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[10px] text-white/40 mt-4 text-center">Status diperbarui otomatis setiap 30 detik.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
