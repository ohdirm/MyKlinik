@extends('layouts.app')
@section('title', 'Status Dokter — MyKlinik911')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6 flex">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group text-sm font-medium transition-all" style="color: var(--ui-text-muted);">
                <div class="w-8 h-8 rounded-full border border-[#e2efe7] dark:border-[#283731] flex items-center justify-center transition-all group-hover:border-brand group-hover:bg-brand group-hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </div>
                <span class="group-hover:text-brand">Kembali ke Beranda</span>
            </a>
        </div>

        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Status Dokter</h1>
            <p class="text-gray-500 dark:text-gray-400">Pantau ketersediaan dokter secara real-time</p>
        </div>
        <div x-data="doctorStatus()" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="doctor in doctors" :key="doctor.id">
                <div class="bg-white dark:bg-[#1c2622] rounded-2xl shadow-sm border border-[#e2efe7] dark:border-[#283731] p-6 hover:shadow-md transition-all duration-300 transform"
                     :class="{'scale-[1.02] border-brand/30': doctor.current_status === 'IN_EXAMINATION'}">
                    <div class="flex items-center gap-4 mb-4">
                        <template x-if="doctor.photo">
                            <img :src="'/storage/' + doctor.photo" :alt="doctor.name" class="w-14 h-14 rounded-full object-cover shrink-0 border-2 border-brand/20">
                        </template>
                        <template x-if="!doctor.photo">
                            <div class="w-14 h-14 rounded-full bg-brand-light dark:bg-brand/10 text-brand-dark dark:text-brand flex items-center justify-center font-bold text-lg shrink-0" x-text="doctor.initials"></div>
                        </template>
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white" x-text="doctor.name"></h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400" x-text="doctor.specialization_label"></p>
                        </div>
                    </div>
                    <span :class="'inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full transition-colors duration-500 ' + doctor.status_badge_class">
                        <span class="w-2 h-2 rounded-full relative">
                            <span class="absolute inset-0 rounded-full bg-current opacity-75 animate-ping" x-show="doctor.current_status === 'IN_EXAMINATION'"></span>
                            <span :class="'relative inline-flex w-2 h-2 rounded-full ' + getDotClass(doctor.current_status)"></span>
                        </span>
                        <span x-text="doctor.status_label + (doctor.current_status === 'IN_EXAMINATION' && doctor.current_queue_number ? ' — Antrian No. ' + doctor.current_queue_number : '')"></span>
                    </span>
                </div>
            </template>
        </div>

        <div class="mt-8 flex flex-col items-center gap-2">
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <span class="flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-brand opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                </span>
                <span>Pembaruan Real-time</span>
            </div>
            <p class="text-xs text-gray-400 italic">Antrean diperbarui setiap 5 detik</p>
        </div>
    </div>
</div>

@php
    $doctorsJson = $doctors->map(function ($d) {
        return [
            'id' => $d->id,
            'name' => $d->name,
            'initials' => $d->initials,
            'photo' => $d->photo,
            'specialization_label' => $d->specialization_label,
            'current_status' => $d->status->current_status ?? 'AVAILABLE',
            'current_queue_number' => $d->status->current_queue_number ?? null,
            'status_label' => $d->status->status_label ?? 'Tersedia',
            'status_badge_class' => $d->status->status_badge_class ?? 'bg-green-100 text-green-800',
        ];
    });
@endphp

@push('scripts')
<script>
    function doctorStatus() {
        return {
            doctors: @json($doctorsJson),
            init() {
                this.poll();
                setInterval(() => this.poll(), 5000);
            },
            poll() {
                fetch('/api/doctor-status')
                    .then(r => r.json())
                    .then(data => {
                        this.doctors = data;
                    })
                    .catch(() => console.warn('Gagal memproses update status dokter.'));
            },
            getDotClass(status) {
                switch(status) {
                    case 'IN_EXAMINATION': return 'bg-yellow-500';
                    case 'UNAVAILABLE': return 'bg-red-500';
                    case 'NEXT_AVAILABLE': return 'bg-blue-500';
                    default: return 'bg-green-500';
                }
            }
        }
    }
</script>
@endpush
@endsection
