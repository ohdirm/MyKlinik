@extends('layouts.admin')
@section('title', 'Kelola Jadwal — MyKlinik911')
@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Jadwal Dokter</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola jadwal praktik per dokter</p>
    </div>
    <a href="{{ route('admin.schedules.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Tambah Jadwal
    </a>
</div>

@if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">{{ session('success') }}</div>
@endif

@php
$dayNames = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
@endphp

<div class="space-y-4">
    @forelse($doctors as $doctor)
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm overflow-hidden transition-colors duration-200">
        {{-- Doctor Header --}}
        <div class="flex items-center justify-between px-5 py-4 bg-gray-50 dark:bg-gray-950 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand to-teal-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($doctor->name, 3, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $doctor->name }}</p>
                    <p class="text-xs text-teal-600 dark:text-teal-400">{{ $doctor->specialization_label }}</p>
                </div>
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">{{ $doctor->schedules->count() }} jadwal aktif</span>
        </div>

        {{-- Schedules --}}
        @if($doctor->schedules->isEmpty())
            <div class="px-5 py-6 text-center text-sm text-gray-400 dark:text-gray-500">
                Belum ada jadwal. <a href="{{ route('admin.schedules.create') }}?doctor={{ $doctor->id }}" class="text-brand dark:text-blue-400 hover:underline">Tambah sekarang</a>
            </div>
        @else
            <div class="divide-y divide-gray-50 dark:divide-gray-800">
                @foreach($doctor->schedules as $s)
                <div class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-850/50 transition-colors">
                    <div class="flex items-center gap-4">
                        {{-- Day badge --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 min-w-[75px] justify-center shadow-sm">
                            {{ $dayNames[$s->day_of_week] }}
                        </span>
                        {{-- Time --}}
                        <div class="flex items-center gap-1.5 text-sm text-gray-700 dark:text-gray-300">
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="font-medium">{{ $s->start_time }}</span>
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                            <span class="font-medium">{{ $s->end_time }}</span>
                        </div>
                        {{-- Max patients --}}
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-3.5 h-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            Maks <strong>{{ $s->max_patients }}</strong> pasien
                        </div>
                    </div>
                    {{-- Actions --}}
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.schedules.edit', $s) }}"
                           class="inline-flex items-center gap-1 rounded-lg border border-brand/20 bg-brand/10 dark:bg-brand/20 px-3 py-1 text-xs font-semibold text-brand dark:text-blue-300 hover:bg-brand/20 dark:hover:bg-brand/35 transition">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.schedules.destroy', $s) }}" onsubmit="return confirm('Hapus jadwal {{ $dayNames[$s->day_of_week] }} untuk {{ $doctor->name }}?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-red-200 dark:border-red-900/35 bg-red-50 dark:bg-red-950/30 px-3 py-1 text-xs font-semibold text-red-600 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-950/50 transition">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
    @empty
        <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-12 text-center text-gray-400 dark:text-gray-500 transition-colors duration-200">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
            <p class="font-medium text-gray-900 dark:text-white">Belum ada dokter aktif</p>
            <p class="text-sm mt-1">Tambahkan dokter terlebih dahulu sebelum mengatur jadwal.</p>
        </div>
    @endforelse
</div>

@endsection
