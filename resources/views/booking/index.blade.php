@extends('layouts.app')
@section('title', 'Pendaftaran Online — MyKlinik911')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-[#f2faf5] via-white to-[#e8f5ed] dark:from-[#141b18] dark:via-[#0a0f0d] dark:to-[#141b18] py-10 transition-colors duration-200"
     x-data="bookingWizard()"
     x-init="init()">

    <div class="max-w-2xl mx-auto px-4">
        {{-- Back Button --}}
        <div class="mb-6 flex">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group text-sm font-medium transition-all" style="color: var(--ui-text-muted);">
                <div class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-800 flex items-center justify-center transition-all group-hover:border-brand group-hover:bg-brand group-hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </div>
                <span class="group-hover:text-brand">Kembali ke Beranda</span>
            </a>
        </div>        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 bg-brand/10 text-brand-dark dark:bg-brand/20 dark:text-brand text-xs font-semibold px-4 py-1.5 rounded-full mb-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Pendaftaran Online
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Buat Janji Temu</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Selesaikan dalam 2 langkah mudah</p>
        </div>
 
        {{-- Step Indicator --}}
        <div class="mb-10">
            {{-- Unified Step Line and Circles --}}
            <div class="relative flex items-center justify-between max-w-xs mx-auto">
                {{-- Background Line --}}
                <div class="absolute top-1/2 left-0 w-full h-1 bg-gray-200 dark:bg-gray-800 -translate-y-1/2 rounded-full overflow-hidden">
                    <div class="h-full bg-brand transition-all duration-500 ease-out" :style="`width: ${step === 1 ? '0%' : '100%'}`"></div>
                </div>

                {{-- Circle 1 --}}
                <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-500 border-2"
                     :class="step > 1 ? 'bg-brand border-brand text-white' : (step === 1 ? 'bg-brand border-brand text-white shadow-xl scale-110 shadow-brand/20' : 'bg-transparent border-gray-300 dark:border-gray-700 text-gray-400')">
                    <template x-if="step > 1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </template>
                    <template x-if="step <= 1">
                        <span>1</span>
                    </template>
                </div>

                {{-- Circle 2 --}}
                <div class="relative z-10 w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-500 border-2"
                     :class="step === 2 ? 'bg-brand border-brand text-white shadow-xl scale-110 shadow-brand/20' : (step > 2 ? 'bg-brand border-brand text-white' : 'bg-white dark:bg-[#141b18] border-gray-300 dark:border-gray-700 text-gray-400')">
                    <span>2</span>
                </div>
            </div>

            {{-- Labels --}}
            <div class="flex justify-between max-w-xs mx-auto mt-3 px-1">
                <span class="text-[10px] font-bold uppercase tracking-widest transition-colors duration-300" :class="step >= 1 ? 'text-brand' : 'text-gray-400'">Jadwal</span>
                <span class="text-[10px] font-bold uppercase tracking-widest transition-colors duration-300" :class="step === 2 ? 'text-brand' : 'text-gray-400'">Data Pasien</span>
            </div>
        </div>

        {{-- Error bag --}}
        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Card --}}
        <div class="bg-white dark:bg-[#1c2622] rounded-3xl shadow-xl overflow-hidden border border-[#e2efe7] dark:border-[#283731] transition-all duration-300">
            <form method="POST" action="{{ route('booking.store') }}" id="booking-form" @submit="handleSubmit">
                @csrf

                {{-- ══ STEP 1: Jadwal & Dokter ══ --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-gradient-to-r from-brand to-teal-600 px-6 py-4">
                        <h2 class="text-white font-semibold text-lg">Langkah 1: Pilih Jadwal & Dokter</h2>
                        <p class="text-white/70 text-xs mt-0.5">Tentukan kapan dan dengan siapa Anda ingin periksa</p>
                    </div>
                    <div class="p-6 space-y-5">
                        {{-- Tanggal --}}
                        <div class="bg-brand/5 dark:bg-brand/10 p-5 rounded-3xl border border-brand/20 mb-2">
                            <label for="exam-date" class="block text-sm font-bold text-brand-dark dark:text-brand mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-xs">1</span>
                                Tentukan Tanggal Periksa <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="exam_date" id="exam-date" x-model="examDate" @change="onDateChange()"
                                   class="input-base py-3 px-4 rounded-2xl bg-white dark:bg-[#141b18] focus:ring-1 focus:ring-brand focus:border-brand"
                                   min="{{ now()->format('Y-m-d') }}"
                                   max="{{ now()->addDays(14)->format('Y-m-d') }}"
                                   value="{{ old('exam_date') }}" required>
                        </div>

                        {{-- Dokter --}}
                        <div x-show="examDate" x-transition>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-xs">2</span>
                                Pilih Dokter Spesialis / Umum <span class="text-red-500">*</span>
                            </label>

                            {{-- ── Complaint Analysis / Doctor Suggestion ── --}}
                            <div class="bg-[#F6FBF8] dark:bg-[#1c2622]/30 border border-[#e2efe7] dark:border-[#283731] rounded-3xl p-5 mb-5 shadow-sm">
                                <div class="flex items-center justify-between mb-3">
                                    <label class="block text-xs font-black text-brand-dark dark:text-brand uppercase tracking-wider flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                        Punya keluhan spesifik?
                                    </label>
                                    <span class="text-[10px] text-gray-400 font-medium italic">Tulis keluhan untuk rekomendasi dokter AI</span>
                                </div>

                                {{-- Quick Tags --}}
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    <template x-for="tag in ['Demam', 'Batuk Pilek', 'Sakit Gigi', 'Pusing', 'Sakit Perut', 'Gatal-gatal']">
                                        <button type="button" @click="complaint = (complaint ? complaint + ', ' : '') + tag" 
                                                class="px-2.5 py-1 rounded-full border border-brand/20 bg-white dark:bg-[#141b18] text-[10px] font-bold text-brand hover:bg-brand hover:text-white transition-all cursor-pointer active:scale-95 shadow-sm">
                                            + <span x-text="tag"></span>
                                        </button>
                                    </template>
                                </div>

                                <div class="flex gap-3 items-center">
                                    <textarea x-model="complaint" rows="2" class="flex-1 input-base text-sm py-3 px-4 resize-none rounded-2xl bg-white dark:bg-[#141b18] focus:ring-1 focus:ring-brand focus:border-brand" placeholder="Contoh: sakit perut, demam tinggi, pusing..."></textarea>
                                    <button type="button" @click="analyzeComplaint()" class="w-16 h-16 bg-[#A8D5BA] hover:bg-[#96c4a9] text-[#1b2621] rounded-2xl flex flex-col items-center justify-center gap-1 shrink-0 shadow-sm transition active:scale-95 cursor-pointer border-0 disabled:opacity-50" :disabled="analyzing">
                                        <svg class="w-5 h-5 transition-all" :class="analyzing ? 'scale-0 opacity-0' : 'scale-100 opacity-100'" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" x-show="!analyzing"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                                        <svg class="animate-spin w-5 h-5 text-[#1b2621] absolute" fill="none" viewBox="0 0 24 24" x-show="analyzing"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                        <span class="text-[9px] font-black uppercase tracking-wider" x-text="analyzing ? '...' : 'Cari'"></span>
                                    </button>
                                </div>

                                {{-- Suggestion result --}}
                                <div x-show="suggestion" class="mt-4 p-4 rounded-2xl border text-xs space-y-2 bg-white dark:bg-[#141b18] shadow-sm border-[#e2efe7] dark:border-[#283731] animate-fade-in" x-transition>
                                    <div class="flex items-start gap-3">
                                        <span class="text-brand text-lg">💡</span>
                                        <div class="flex-1">
                                            <p class="font-bold text-gray-900 dark:text-white" x-text="`Saran: ${suggestion?.suggested_doctor?.name}`"></p>
                                            <p class="text-gray-500 dark:text-gray-400 italic mt-1 leading-relaxed" x-text="suggestion?.matched_specialization ? `Gejala Anda cocok dengan spesialisasi ${suggestion.matched_specialization.label}` : 'Dokter ini dapat melayani keluhan Anda.'"></p>
                                            <p x-show="suggestion?.fallback" class="text-amber-600 dark:text-amber-400 font-semibold mt-1" x-text="suggestion?.fallback_reason"></p>
                                            <button type="button" @click="applySuggestion()" class="mt-3 w-full bg-brand/10 dark:bg-brand/20 text-brand-dark dark:text-brand py-2.5 rounded-xl text-xs font-bold hover:bg-brand hover:text-[#1b2621] transition-all duration-200 cursor-pointer flex items-center justify-center gap-2 border border-brand/20">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                Pilih Dokter Ini & Gunakan Jadwal
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Search & Filter --}}
                            <div class="flex gap-3 mb-4">
                                <div class="relative flex-1">
                                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                    <input type="text" x-model="doctorSearch" placeholder="Atau cari nama dokter di sini..."
                                           class="input-base pl-11 text-sm py-3 rounded-2xl bg-white dark:bg-[#141b18] focus:ring-1 focus:ring-brand focus:border-brand">
                                </div>
                                <select x-model="spFilter" class="input-base text-sm w-48 py-3 rounded-2xl bg-white dark:bg-[#141b18] focus:ring-1 focus:ring-brand focus:border-brand">
                                    <option value="">Semua Spesialis</option>
                                    @foreach($doctors->pluck('specialization_label')->unique()->sort() as $sp)
                                        <option value="{{ $sp }}">{{ $sp }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Doctor Cards (scrollable list) --}}
                            <div class="space-y-3 max-h-80 overflow-y-auto pr-2 rounded-2xl scrollbar-custom" id="doctor-cards">
                                {{-- Skeleton Loader --}}
                                <template x-if="fetchingCapacities">
                                    <div class="space-y-3">
                                        <template x-for="i in 3">
                                            <div class="h-20 bg-gray-100 dark:bg-[#1c2622] rounded-3xl animate-pulse flex items-center px-4 gap-4">
                                                <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-800"></div>
                                                <div class="flex-1 space-y-2">
                                                    <div class="h-3 w-1/3 bg-gray-200 dark:bg-gray-800 rounded"></div>
                                                    <div class="h-2 w-1/4 bg-gray-200 dark:bg-gray-800 rounded"></div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <div x-show="!fetchingCapacities">
                                @foreach($doctors as $doc)
                                <label class="relative flex items-center gap-4 p-4 rounded-3xl border-2 cursor-pointer transition-all duration-200 hover:border-brand hover:bg-brand/5 dark:hover:bg-brand/10 group"
                                       :class="doctorId == '{{ $doc->id }}' ? 'border-brand bg-brand/5 dark:bg-brand/10 shadow-sm' : 'border-[#e2efe7] dark:border-[#283731] bg-white dark:bg-[#141b18]'"
                                       x-show="(doctorSearch === '' || '{{ strtolower($doc->name) }}'.includes(doctorSearch.toLowerCase())) && (spFilter === '' || spFilter === '{{ $doc->specialization_label }}')"
                                       >
                                    <input type="radio" name="doctor_id" value="{{ $doc->id }}" class="sr-only" x-model="doctorId" @change="onDoctorChange()" {{ old('doctor_id') == $doc->id ? 'checked' : '' }}>
                                    
                                    {{-- Avatar circle with initials or photo --}}
                                    <div class="w-12 h-12 rounded-full bg-brand/20 dark:bg-brand/10 text-brand-dark dark:text-brand flex items-center justify-center font-bold text-sm shrink-0 border-0 overflow-hidden">
                                        @if($doc->photo_url)
                                            <img src="{{ $doc->photo_url }}" alt="{{ $doc->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $doc->initials }}
                                        @endif
                                    </div>
                                    
                                    {{-- Doctor Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-bold text-gray-900 dark:text-white text-sm leading-tight">{{ $doc->name }}</p>
                                                <p class="text-xs text-brand-dark dark:text-brand font-semibold mt-1">{{ $doc->specialization_label }}</p>
                                            </div>
                                            <template x-if="examDate && doctorCapacities['{{ $doc->id }}']">
                                                <div class="flex flex-col items-end">
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full transition-all duration-300"
                                                          :class="doctorCapacities['{{ $doc->id }}'].total_remaining > 0 ? 'bg-brand/10 text-brand-dark dark:bg-brand/20 dark:text-brand' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'"
                                                          x-text="doctorCapacities['{{ $doc->id }}'].total_remaining > 0 ? `Sisa: ${doctorCapacities['{{ $doc->id }}'].total_remaining} slot` : 'Penuh'">
                                                    </span>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    {{-- Radio indicator --}}
                                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-all"
                                         :class="doctorId == '{{ $doc->id }}' ? 'border-brand bg-brand' : 'border-[#e2efe7] dark:border-[#283731] bg-white dark:bg-[#141b18]'">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="doctorId == '{{ $doc->id }}'"></div>
                                    </div>
                                </label>
                                @endforeach
                                </div>
                                
                                <p x-show="!fetchingCapacities && [...$el.parentElement.querySelectorAll('label')].every(l => l.style.display === 'none')"
                                   class="text-center text-sm text-gray-400 py-6">Dokter tidak ditemukan</p>
                            </div>
                        </div>


                        {{-- Jadwal --}}
                        <div x-show="doctorId && examDate" x-transition>
                            <label for="select-jadwal" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-xs">3</span>
                                Pilih Jadwal Waktu <span class="text-red-500">*</span>
                            </label>
                            <div x-show="loadingSchedule" class="flex items-center gap-2 text-sm text-gray-400 py-3">
                                <svg class="animate-spin w-4 h-4 text-brand" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                Memuat jadwal...
                            </div>
                            <select name="schedule_id" id="select-jadwal" class="input-base py-3 px-4 rounded-2xl bg-white dark:bg-[#141b18] focus:ring-1 focus:ring-brand focus:border-brand" :disabled="schedules.length === 0" x-show="!loadingSchedule" required>
                                <option value="">— Pilih jadwal —</option>
                                <template x-for="s in schedules" :key="s.id">
                                    <option :value="s.id" :disabled="s.remaining_capacity <= 0"
                                            x-text="`${s.day_name} — ${s.start_time} - ${s.end_time} (Sisa: ${s.remaining_capacity} dari ${s.max_patients} pasien)`"></option>
                                </template>
                            </select>
                            <p x-show="!loadingSchedule && schedules.length === 0 && (doctorId && examDate)" class="text-xs text-amber-600 mt-1">Tidak ada jadwal dokter pada hari ini.</p>
                        </div>
                    </div>
                </div>

                {{-- ══ STEP 2: Data Pasien ══ --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-gradient-to-r from-brand to-teal-600 px-6 py-4">
                        <h2 class="text-white font-semibold text-lg">Langkah 2: Data Pasien</h2>
                        <p class="text-white/85 text-xs mt-0.5">Isi data diri dan domisili pasien</p>
                    </div>
                    <div class="p-6 space-y-5">

                        {{-- Patient Type Selector --}}
                        <input type="hidden" name="profile_type" :value="profileType">
                        <input type="hidden" name="family_profile_id" :value="profileType === 'family' ? selectedFamilyId : ''">

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-xs">1</span>
                                Siapa yang akan diperiksa? <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all active:scale-95 bg-white dark:bg-[#141b18]"
                                       :class="profileType === 'self' ? 'border-brand bg-brand/5 dark:bg-brand/10 shadow-sm' : 'border-[#e2efe7] dark:border-[#283731] hover:border-brand/40'">
                                    <input type="radio" value="self" class="sr-only" x-model="profileType" @change="onProfileTypeChange()">
                                    <div class="w-10 h-10 rounded-full bg-brand/10 dark:bg-brand/20 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-brand-dark dark:text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-sm text-gray-700 dark:text-gray-300 block">Diri Sendiri</span>
                                        <span class="text-[10px] text-gray-400">Data dari profile</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all active:scale-95 bg-white dark:bg-[#141b18]"
                                       :class="profileType === 'family' ? 'border-brand bg-brand/5 dark:bg-brand/10 shadow-sm' : 'border-[#e2efe7] dark:border-[#283731] hover:border-brand/40'"
                                       @if($familyProfiles->isEmpty()) title="Tambahkan anggota keluarga di menu Profile terlebih dahulu" @endif>
                                    <input type="radio" value="family" class="sr-only" x-model="profileType" @change="onProfileTypeChange()" @if($familyProfiles->isEmpty()) disabled @endif>
                                    <div class="w-10 h-10 rounded-full bg-brand/10 dark:bg-brand/20 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-brand-dark dark:text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-sm text-gray-700 dark:text-gray-300 block">Anggota Keluarga</span>
                                        <span class="text-[10px] text-gray-400">{{ $familyProfiles->count() }} anggota</span>
                                    </div>
                                </label>
                            </div>
                            @if($familyProfiles->isEmpty())
                            <p class="text-xs text-amber-600 dark:text-amber-400 mt-2 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                <a href="{{ route('profile.index', ['tab' => 'family']) }}" class="underline hover:text-amber-700">Tambahkan anggota keluarga</a> di menu Profile untuk booking atas nama mereka.
                            </p>
                            @endif
                        </div>

                        {{-- Family Member Dropdown --}}
                        <div x-show="profileType === 'family'" x-transition>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-brand text-white flex items-center justify-center text-xs">2</span>
                                Pilih Anggota Keluarga <span class="text-red-500">*</span>
                            </label>
                            <select x-model="selectedFamilyId" @change="onFamilySelect()" class="input-base py-3 px-4 rounded-2xl bg-white dark:bg-[#141b18] focus:ring-1 focus:ring-brand focus:border-brand">
                                <option value="">— Pilih anggota keluarga —</option>
                                @foreach($familyProfiles as $fp)
                                <option value="{{ $fp->id }}">{{ $fp->full_name }} ({{ $fp->relationship }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Auto-fill Info Banner --}}
                        <div x-show="profileType === 'self' || (profileType === 'family' && selectedFamilyId)" x-transition
                             class="bg-brand/5 dark:bg-brand/10 border border-brand/20 rounded-2xl px-4 py-3 flex items-center gap-3">
                            <svg class="w-5 h-5 text-brand shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-brand-dark dark:text-brand font-medium">Data pasien terisi otomatis dari profile. Anda dapat mengubahnya jika diperlukan.</p>
                        </div>

                        {{-- Patient Fields --}}
                        <div>
                            <label for="nik" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" id="nik" class="input-base" maxlength="16" placeholder="Masukkan 16 digit NIK" x-model="patientNik" required>
                        </div>
                        <div>
                            <label for="patient_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Pasien <span class="text-red-500">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" class="input-base" placeholder="Nama lengkap sesuai KTP" x-model="patientName" required>
                        </div>
                        <div>
                            <label for="birth_date_input" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="birth_date" id="birth_date_input" class="input-base" x-model="patientBirthDate" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all active:scale-95 bg-white dark:bg-[#141b18]"
                                       :class="gender === 'L' ? 'border-brand bg-brand/5 dark:bg-brand/10 shadow-sm' : 'border-[#e2efe7] dark:border-[#283731] hover:border-brand/40 dark:hover:border-brand/40'">
                                    <input type="radio" name="gender" value="L" class="sr-only" x-model="gender">
                                    <span class="text-xl">👨</span>
                                    <span class="font-medium text-sm text-gray-700 dark:text-gray-300">Laki-laki</span>
                                </label>
                                <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all active:scale-95 bg-white dark:bg-[#141b18]"
                                       :class="gender === 'P' ? 'border-brand bg-brand/5 dark:bg-brand/10 shadow-sm' : 'border-[#e2efe7] dark:border-[#283731] hover:border-brand/40 dark:hover:border-brand/40'">
                                    <input type="radio" name="gender" value="P" class="sr-only" x-model="gender">
                                    <span class="text-xl">👩</span>
                                    <span class="font-medium text-sm text-gray-700 dark:text-gray-300">Perempuan</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">No HP/WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" id="phone" class="input-base" maxlength="15" placeholder="08xxxxxxxxxx" x-model="patientPhone" required>
                        </div>
                        {{-- Hidden Complaint Field for Form Submit --}}
                        <input type="hidden" name="complaint" :value="complaint">

                        {{-- ══ Divider: Domisili ══ --}}
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-[#e2efe7] dark:border-[#283731]"></div></div>
                            <div class="relative flex justify-start">
                                <span class="bg-white dark:bg-[#1c2622] pr-3 text-xs font-semibold text-brand-dark dark:text-brand uppercase tracking-wider">📍 Domisili</span>
                            </div>
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" rows="3" class="input-base" placeholder="Nama jalan, nomor rumah, RT/RW" required>{{ old('address', $profile?->address ?? '') }}</textarea>
                        </div>

                        {{-- Provinsi --}}
                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <select name="province" id="province" class="input-base" x-model="address.province" @change="onProvinceChange()" required>
                                <option value="">— Pilih Provinsi —</option>
                                <template x-for="p in provinces" :key="p.id">
                                    <option :value="p.name" :data-id="p.id" x-text="p.name" :selected="p.name === address.province"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Kabupaten/Kota --}}
                        <div>
                            <label for="district" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select name="district" id="district" class="input-base" x-model="address.district" @change="onDistrictChange()" :disabled="!address.province || loadingWilayah.districts" required>
                                <option value="" x-text="loadingWilayah.districts ? 'Memuat...' : '— Pilih Kabupaten —'"></option>
                                <template x-for="d in districts" :key="d.id">
                                    <option :value="d.name" :data-id="d.id" x-text="d.name" :selected="d.name === address.district"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Kecamatan --}}
                        <div>
                            <label for="sub_district" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="sub_district" id="sub_district" class="input-base" x-model="address.sub_district" @change="onSubDistrictChange()" :disabled="!address.district || loadingWilayah.subdistricts" required>
                                <option value="" x-text="loadingWilayah.subdistricts ? 'Memuat...' : '— Pilih Kecamatan —'"></option>
                                <template x-for="s in subdistricts" :key="s.id">
                                    <option :value="s.name" :data-id="s.id" x-text="s.name" :selected="s.name === address.sub_district"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Kelurahan/Desa --}}
                        <div>
                            <label for="village" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                            <select name="village" id="village" class="input-base" x-model="address.village" :disabled="!address.sub_district || loadingWilayah.villages" required>
                                <option value="" x-text="loadingWilayah.villages ? 'Memuat...' : '— Pilih Kelurahan —'"></option>
                                <template x-for="v in villages" :key="v.id">
                                    <option :value="v.name" :data-id="v.id" x-text="v.name" :selected="v.name === address.village"></option>
                                </template>
                            </select>
                        </div>

                        {{-- Ringkasan Booking --}}
                        <div class="bg-[#F6FBF8] dark:bg-[#141b18] rounded-xl p-4 border border-[#e2efe7] dark:border-[#283731] text-sm space-y-2">
                            <p class="font-semibold text-gray-700 dark:text-gray-300 mb-2">📋 Ringkasan Booking</p>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400"><span>Dokter</span><span class="font-medium text-gray-900 dark:text-white" x-text="selectedDoctorName || '—'"></span></div>
                            <div class="flex justify-between text-gray-600 dark:text-gray-400"><span>Tanggal</span><span class="font-medium text-gray-900 dark:text-white" x-text="examDate ? new Date(examDate).toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'}) : '—'"></span></div>
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="px-6 pb-6 flex gap-3">
                    <button type="button" x-show="step > 1" @click="step--"
                            class="flex-1 py-3.5 rounded-xl border font-semibold transition-all active:scale-95 text-sm"
                            style="background-color: var(--ui-surface); border-color: var(--ui-border); color: var(--ui-text);">
                        ← Sebelumnya
                    </button>
                    <button type="button" x-show="step < 2" @click="nextStep()"
                            class="flex-1 btn-primary py-3 text-sm font-semibold">
                        Selanjutnya →
                    </button>
                    <button type="submit" x-show="step === 2"
                            :disabled="submitting"
                            class="flex-1 bg-gradient-to-r from-brand to-[#85cca0] hover:from-[#96d7af] hover:to-brand text-white font-bold py-3 rounded-xl transition text-sm shadow-sm active:scale-95 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!submitting">
                            <span>Daftar Sekarang</span>
                        </template>
                        <template x-if="submitting">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                <span>Memproses...</span>
                            </div>
                        </template>
                    </button>
                </div>
            </form>
        </div>

        {{-- Info footer --}}
        <p class="text-center text-xs text-gray-400 mt-4">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
            Butuh bantuan? Hubungi 0812-3456-7890
        </p>
    </div>
</div>

{{-- Success Modal --}}
@if(session('booking'))
@php $bk = session('booking'); @endphp
<div id="success-modal" class="fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-50 p-4" x-data="{ printing: false }">
    <div class="bg-white dark:bg-[#1c2622] rounded-3xl p-8 max-w-md w-full shadow-2xl text-center border border-[#e2efe7] dark:border-[#283731] animate-bounce-in overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-brand"></div>
        
        <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>
        
        <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-1">Pendaftaran Berhasil!</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-6 uppercase tracking-widest font-bold">Kode Booking: <span class="text-brand">{{ $bk->booking_code }}</span></p>
        
        {{-- Printable Ticket Card --}}
        <div id="printable-ticket" class="bg-brand/5 dark:bg-[#141b18] rounded-2xl p-6 mb-6 text-left border-2 border-dashed border-brand/20 relative">
            <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-[#1c2622] rounded-full border-r-2 border-brand/10"></div>
            <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-white dark:bg-[#1c2622] rounded-full border-l-2 border-brand/10"></div>
            
            <div class="text-center mb-4 border-b border-brand/10 pb-4">
                <span class="text-[10px] text-gray-400 uppercase font-bold">Nomor Antrean</span>
                <div class="text-6xl font-black text-brand leading-none my-1">{{ $bk->queue_number }}</div>
                <div class="text-[10px] font-bold text-brand-dark dark:text-brand bg-brand/10 px-3 py-1 rounded-full inline-block mt-2">
                    ESTIMASI: {{ $bk->estimated_time ?? '--:--' }} WIB
                </div>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between"><span class="text-gray-500">Pasien</span><span class="font-bold text-gray-900 dark:text-white">{{ $bk->patient_name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Dokter</span><span class="font-bold text-gray-900 dark:text-white">{{ $bk->doctor->name }}</span></div>
                <div class="flex justify-between"><span class="text-gray-500">Jadwal</span><span class="font-bold text-gray-900 dark:text-white">{{ $bk->exam_date->translatedFormat('l, d M Y') }}</span></div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <button onclick="window.print()" class="btn-outline flex-1 py-3 text-xs font-bold border-brand/30 text-brand flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.844l9.635-9.635m-9.635 9.635a2.592 2.592 0 003.665 3.665l9.635-9.635m-13.3 0L3 16.5m13.3-8.88l4.419 4.419"/></svg>
                Cetak Tiket
            </button>
            <a href="{{ route('patient.dashboard') }}" class="btn-primary flex-1 py-3 text-xs font-bold">Dashboard</a>
        </div>
        <button onclick="document.getElementById('success-modal').remove()" class="mt-4 text-[10px] font-bold text-gray-400 hover:text-gray-600 transition-colors uppercase tracking-widest">Tutup Jendela Ini</button>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #success-modal, #success-modal * { visibility: visible; }
    #success-modal { position: absolute; left: 0; top: 0; width: 100%; padding: 0; background: white; }
    #printable-ticket { border: 2px solid #4A7C66; background: transparent !important; }
    .btn-outline, .btn-primary, button { display: none !important; }
}
</style>
@endif

{{-- Toast Container --}}
<div x-data="toastManager()" 
     @toast.window="add($event.detail)"
     class="fixed bottom-6 right-6 z-[60] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
    <template x-for="t in toasts" :key="t.id">
        <div x-show="t.show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="pointer-events-auto bg-white dark:bg-[#1c2622] shadow-2xl rounded-2xl p-4 flex items-center gap-3 border-l-4"
             :class="t.type === 'error' ? 'border-red-500' : 'border-brand'">
            <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" :class="t.type === 'error' ? 'bg-red-100 text-red-600' : 'bg-brand/10 text-brand'">
                <template x-if="t.type === 'error'"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></template>
                <template x-if="t.type !== 'error'"><svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></template>
            </div>
            <p class="text-xs font-bold text-gray-700 dark:text-gray-200" x-text="t.message"></p>
        </div>
    </template>
</div>

@push('scripts')
    @php
        $selfProfileJson = [
            'full_name' => $profile?->full_name ?? Auth::user()->name,
            'nik' => $profile?->nik ?? '',
            'birth_date' => $profile?->birth_date?->format('Y-m-d') ?? '',
            'gender' => $profile?->gender ?? '',
            'phone_number' => $profile?->phone_number ?? '',
        ];
    @endphp
    @vite('resources/js/app.js') {{-- booking.js functionality is integrated here --}}
    <script>
    function toastManager() {
        return {
            toasts: [],
            add(detail) {
                const id = Date.now();
                this.toasts.push({ id, message: detail.message, type: detail.type || 'success', show: true });
                setTimeout(() => {
                    const idx = this.toasts.findIndex(t => t.id === id);
                    if (idx !== -1) this.toasts[idx].show = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 500);
                }, 4000);
            }
        };
    }

    function bookingWizard() {
        return {
            step: 1,
            doctorId: '{{ old('doctor_id', '') }}',
            examDate: '{{ old('exam_date', '') }}',
            gender: '{{ old('gender', $profile?->gender ?? '') }}',
            schedules: [],
            loadingSchedule: false,
            selectedDoctorName: '',
            doctorSearch: '',
            spFilter: '',
            complaint: '{{ old('complaint', '') }}',
            analyzing: false,
            suggestion: null,
            doctorCapacities: {},
            fetchingCapacities: false,
            submitting: false,

            // Wilayah logic
            provinces: [],
            districts: [],
            subdistricts: [],
            villages: [],
            loadingWilayah: { provinces: false, districts: false, subdistricts: false, villages: false },
            address: {
                province: "{{ old('province', $profile?->province ?? '') }}",
                district: "{{ old('district', $profile?->district ?? '') }}",
                sub_district: "{{ old('sub_district', $profile?->sub_district ?? '') }}",
                village: "{{ old('village', $profile?->village ?? '') }}"
            },

            // Patient selection
            profileType: '{{ old('profile_type', 'self') }}',
            selectedFamilyId: '{{ old('family_profile_id', '') }}',
            selfProfile: @json($selfProfileJson),
            familyProfilesData: @json($familyProfilesJson),

            // Patient fields
            patientName: '{{ old('patient_name', $profile?->full_name ?? Auth::user()->name) }}',
            patientNik: '{{ old('nik', $profile?->nik ?? '') }}',
            patientBirthDate: '{{ old('birth_date', $profile?->birth_date?->format('Y-m-d') ?? '') }}',
            patientPhone: '{{ old('phone', $profile?->phone_number ?? '') }}',

            init() {
                @if($errors->any())
                    this.step = 2;
                @endif

                if (this.doctorId || this.examDate) {
                    this.loadSchedules();
                }
                if (this.examDate) {
                    this.fetchDoctorCapacities();
                }

                if (this.profileType === 'self') {
                    this.fillFromSelf();
                }

                // Initial Wilayah Load
                this.loadProvinces();
            },

            // Wilayah Methods
            loadProvinces() {
                this.loadingWilayah.provinces = true;
                fetch('/api/wilayah/provinces')
                    .then(r => r.json())
                    .then(data => {
                        this.provinces = data;
                        this.loadingWilayah.provinces = false;
                        if (this.address.province) {
                            const p = this.provinces.find(x => x.name.toLowerCase() === this.address.province.toLowerCase());
                            if (p) this.loadDistricts(p.id);
                        }
                    });
            },

            loadDistricts(provinceId) {
                this.loadingWilayah.districts = true;
                this.districts = [];
                fetch(`/api/wilayah/districts?province_id=${provinceId}`)
                    .then(r => r.json())
                    .then(data => {
                        this.districts = data;
                        this.loadingWilayah.districts = false;
                        if (this.address.district) {
                            const d = this.districts.find(x => x.name.toLowerCase() === this.address.district.toLowerCase());
                            if (d) this.loadSubDistricts(d.id);
                        }
                    });
            },

            loadSubDistricts(districtId) {
                this.loadingWilayah.subdistricts = true;
                this.subdistricts = [];
                fetch(`/api/wilayah/subdistricts?district_id=${districtId}`)
                    .then(r => r.json())
                    .then(data => {
                        this.subdistricts = data;
                        this.loadingWilayah.subdistricts = false;
                        if (this.address.sub_district) {
                            const s = this.subdistricts.find(x => x.name.toLowerCase() === this.address.sub_district.toLowerCase());
                            if (s) this.loadVillages(s.id);
                        }
                    });
            },

            loadVillages(subDistrictId) {
                this.loadingWilayah.villages = true;
                this.villages = [];
                fetch(`/api/wilayah/villages?sub_district_id=${subDistrictId}`)
                    .then(r => r.json())
                    .then(data => {
                        this.villages = data;
                        this.loadingWilayah.villages = false;
                    });
            },

            onProvinceChange() {
                const el = document.getElementById('province');
                const id = el.options[el.selectedIndex]?.dataset?.id;
                this.address.district = '';
                this.address.sub_district = '';
                this.address.village = '';
                if (id) this.loadDistricts(id);
            },

            onDistrictChange() {
                const el = document.getElementById('district');
                const id = el.options[el.selectedIndex]?.dataset?.id;
                this.address.sub_district = '';
                this.address.village = '';
                if (id) this.loadSubDistricts(id);
            },

            onSubDistrictChange() {
                const el = document.getElementById('sub_district');
                const id = el.options[el.selectedIndex]?.dataset?.id;
                this.address.village = '';
                if (id) this.loadVillages(id);
            },

            fillFromSelf() {
                this.patientName = this.selfProfile.full_name || '';
                this.patientNik = this.selfProfile.nik || '';
                this.patientBirthDate = this.selfProfile.birth_date || '';
                this.gender = this.selfProfile.gender || '';
                this.patientPhone = this.selfProfile.phone_number || '';
            },

            fillFromFamily(id) {
                const fp = this.familyProfilesData[id];
                if (!fp) return;
                this.patientName = fp.full_name || '';
                this.patientNik = fp.nik || '';
                this.patientBirthDate = fp.birth_date || '';
                this.gender = fp.gender || '';
                this.patientPhone = fp.phone_number || '';
            },

            onProfileTypeChange() {
                if (this.profileType === 'self') {
                    this.selectedFamilyId = '';
                    this.fillFromSelf();
                } else {
                    this.patientName = '';
                    this.patientNik = '';
                    this.patientBirthDate = '';
                    this.gender = '';
                    this.patientPhone = '';
                }
            },

            onFamilySelect() {
                if (this.selectedFamilyId) {
                    this.fillFromFamily(this.selectedFamilyId);
                }
            },

            onDoctorChange() {
                this.selectedDoctorName = document.querySelector(`input[name=doctor_id][value="${this.doctorId}"]`)?.closest('label')?.querySelector('.font-bold')?.textContent ?? '';
                this.loadSchedules();
            },

            onDateChange() {
                this.loadSchedules();
                this.fetchDoctorCapacities();
            },

            loadSchedules() {
                if (!this.doctorId || !this.examDate) return;
                this.loadingSchedule = true;
                this.schedules = [];
                const date = new Date(this.examDate);
                const dayOfWeek = date.getDay();
                fetch(`/api/schedules/${this.doctorId}?date=${this.examDate}`)
                    .then(r => r.json())
                    .then(data => {
                        this.schedules = data.filter(s => s.day_of_week === dayOfWeek);
                        this.loadingSchedule = false;
                    })
                    .catch(() => { this.loadingSchedule = false; });
            },

            fetchDoctorCapacities() {
                if (!this.examDate) return;
                this.fetchingCapacities = true;
                fetch(`/api/doctor-capacities?date=${this.examDate}`)
                    .then(r => r.json())
                    .then(data => {
                        const capacities = {};
                        data.forEach(item => { capacities[item.doctor_id] = item; });
                        this.doctorCapacities = capacities;
                        this.fetchingCapacities = false;
                    })
                    .catch(() => { this.fetchingCapacities = false; });
            },

            analyzeComplaint() {
                if (this.complaint.trim().length < 3) { 
                    window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Silakan tuliskan keluhan Anda terlebih dahulu.', type: 'error' } }));
                    return; 
                }
                this.analyzing = true;
                this.suggestion = null;

                fetch('/api/suggest-doctor', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ complaint: this.complaint, date: this.examDate || new Date().toISOString().slice(0,10) })
                })
                .then(r => r.json())
                .then(data => {
                    this.analyzing = false;
                    if (data.suggested_doctor) {
                        this.suggestion = data;
                    } else {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Maaf, dokter spesifik tidak ditemukan.', type: 'error' } }));
                    }
                })
                .catch(() => { this.analyzing = false; });
            },

            applySuggestion() {
                if (!this.suggestion) return;
                this.doctorId = this.suggestion.suggested_doctor.id;
                this.spFilter = '';
                this.doctorSearch = '';
                if (!this.examDate) this.examDate = new Date().toISOString().slice(0,10);
                this.schedules = this.suggestion.schedules;
                this.selectedDoctorName = this.suggestion.suggested_doctor.name;
                
                if (this.schedules.length === 1) {
                    setTimeout(() => {
                        const sel = document.getElementById('select-jadwal');
                        if (sel) sel.value = this.schedules[0].id;
                    }, 50);
                }
                this.suggestion = null;
                document.getElementById('select-jadwal').scrollIntoView({ behavior: 'smooth', block: 'center' });
            },

            nextStep() {
                if (this.step === 1) {
                    if (!this.doctorId) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Silakan pilih dokter.', type: 'error' } })); return; }
                    if (!this.examDate) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Silakan pilih tanggal.', type: 'error' } })); return; }
                    const jadwal = document.getElementById('select-jadwal');
                    if (!jadwal.value) { window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Silakan pilih jadwal waktu.', type: 'error' } })); return; }
                }
                this.step++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            },

            handleSubmit(e) {
                this.submitting = true;
            }
        }
    }
    </script>
@endpush
@endsection
