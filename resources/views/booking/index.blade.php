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
            <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm">Selesaikan dalam 3 langkah mudah</p>
        </div>
 
        {{-- Step Indicator --}}
        <div class="flex items-center justify-center mb-8">
            <template x-for="(label, i) in ['Jadwal', 'Data Pasien', 'Domisili']" :key="i">
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 border-2"
                             :class="step > i+1 ? 'bg-brand-dark border-brand-dark text-white' : (step === i+1 ? 'bg-brand border-brand text-white shadow-lg scale-110 shadow-brand/20' : 'bg-transparent border-gray-300 dark:border-gray-700 text-gray-400')">
                            <template x-if="step > i+1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </template>
                            <template x-if="step <= i+1">
                                <span x-text="i+1"></span>
                            </template>
                        </div>
                        <span class="text-xs mt-1 font-medium transition-colors"
                              :class="step === i+1 ? 'text-brand' : 'text-gray-400'" x-text="label"></span>
                    </div>
                    <div x-show="i < 2" class="w-16 h-0.5 mb-4 mx-1 transition-colors duration-300"
                         :class="step > i+1 ? 'bg-brand-dark' : 'bg-gray-200'"></div>
                </div>
            </template>
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
        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-800 transition-all duration-300">
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
                                   class="input-base py-3 px-4 rounded-2xl bg-white dark:bg-gray-950 focus:ring-1 focus:ring-brand focus:border-brand"
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
                                <label class="block text-xs font-black text-brand-dark dark:text-brand uppercase tracking-wider mb-2.5 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-brand-dark dark:text-brand" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                    Punya keluhan spesifik?
                                </label>
                                <div class="flex gap-3 items-center">
                                    <textarea x-model="complaint" rows="2" class="flex-1 input-base text-sm py-3 px-4 resize-none rounded-2xl bg-white dark:bg-gray-950 focus:ring-1 focus:ring-brand focus:border-brand" placeholder="Contoh: sakit perut, demam tinggi, pusing..."></textarea>
                                    <button type="button" @click="analyzeComplaint()" class="w-16 h-16 bg-[#A8D5BA] hover:bg-[#96c4a9] text-[#1b2621] rounded-2xl flex flex-col items-center justify-center gap-1 shrink-0 shadow-sm transition active:scale-95 cursor-pointer border-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" x-show="!analyzing"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                                        <svg class="animate-spin w-5 h-5 text-[#1b2621]" fill="none" viewBox="0 0 24 24" x-show="analyzing"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                        <span class="text-[9px] font-black uppercase tracking-wider" x-text="analyzing ? '...' : 'Cari'"></span>
                                    </button>
                                </div>

                                {{-- Suggestion result --}}
                                <div x-show="suggestion" class="mt-4 p-4 rounded-2xl border text-xs space-y-2 bg-white dark:bg-gray-950 shadow-sm border-gray-100 dark:border-gray-800 animate-fade-in" x-transition>
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
                                           class="input-base pl-11 text-sm py-3 rounded-2xl bg-white dark:bg-gray-950 focus:ring-1 focus:ring-brand focus:border-brand">
                                </div>
                                <select x-model="spFilter" class="input-base text-sm w-48 py-3 rounded-2xl bg-white dark:bg-gray-950 focus:ring-1 focus:ring-brand focus:border-brand">
                                    <option value="">Semua Spesialis</option>
                                    @foreach($doctors->pluck('specialization_label')->unique()->sort() as $sp)
                                        <option value="{{ $sp }}">{{ $sp }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Doctor Cards (scrollable list) --}}
                            <div class="space-y-3 max-h-80 overflow-y-auto pr-2 rounded-2xl scrollbar-thin scrollbar-thumb-brand/20 scrollbar-track-transparent" id="doctor-cards">
                                @foreach($doctors as $doc)
                                <label class="relative flex items-center gap-4 p-4 rounded-3xl border-2 cursor-pointer transition-all duration-200 hover:border-brand hover:bg-brand/5 dark:hover:bg-brand/10 group"
                                       :class="doctorId == '{{ $doc->id }}' ? 'border-brand bg-brand/5 dark:bg-brand/10 shadow-sm' : 'border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-950'"
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
                                         :class="doctorId == '{{ $doc->id }}' ? 'border-brand bg-brand' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-950'">
                                        <div class="w-2 h-2 rounded-full bg-white" x-show="doctorId == '{{ $doc->id }}'"></div>
                                    </div>
                                </label>
                                @endforeach
                                <p x-show="$el.previousElementSibling && [...$el.parentElement.querySelectorAll('label')].every(l => l.style.display === 'none')"
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
                            <select name="schedule_id" id="select-jadwal" class="input-base py-3 px-4 rounded-2xl bg-white dark:bg-gray-950 focus:ring-1 focus:ring-brand focus:border-brand" :disabled="schedules.length === 0" x-show="!loadingSchedule" required>
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
                        <p class="text-white/85 text-xs mt-0.5">Isi data diri pasien yang akan diperiksa</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="nik" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" id="nik" class="input-base" maxlength="16" placeholder="Masukkan 16 digit NIK" value="{{ old('nik') }}" required>
                        </div>
                        <div>
                            <label for="patient_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Pasien <span class="text-red-500">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" class="input-base" placeholder="Nama lengkap sesuai KTP" value="{{ old('patient_name', Auth::user()->name) }}" required>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Terisi otomatis dari akun Anda. Ubah jika mendaftarkan orang lain.</p>
                        </div>
                        <div>
                            <label for="birth_date_input" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="birth_date" id="birth_date_input" class="input-base" value="{{ old('birth_date') }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all active:scale-95"
                                       :class="gender === 'L' ? 'border-brand bg-brand/5 dark:bg-brand/10' : 'border-gray-100 dark:border-gray-800 hover:border-gray-200 dark:hover:border-gray-700'">
                                    <input type="radio" name="gender" value="L" class="sr-only" x-model="gender" {{ old('gender') === 'L' ? 'checked' : '' }}>
                                    <span class="text-xl">👨</span>
                                    <span class="font-medium text-sm text-gray-700 dark:text-gray-300">Laki-laki</span>
                                </label>
                                <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all active:scale-95"
                                       :class="gender === 'P' ? 'border-brand bg-brand/5 dark:bg-brand/10' : 'border-gray-100 dark:border-gray-800 hover:border-gray-200 dark:hover:border-gray-700'">
                                    <input type="radio" name="gender" value="P" class="sr-only" x-model="gender" {{ old('gender') === 'P' ? 'checked' : '' }}>
                                    <span class="text-xl">👩</span>
                                    <span class="font-medium text-sm text-gray-700 dark:text-gray-300">Perempuan</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">No HP/WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" id="phone" class="input-base" maxlength="15" placeholder="08xxxxxxxxxx" value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                        </div>
                        {{-- Hidden Complaint Field for Form Submit --}}
                        <input type="hidden" name="complaint" :value="complaint">
                    </div>
                </div>

                {{-- ══ STEP 3: Domisili & Konfirmasi ══ --}}
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-gradient-to-r from-brand to-teal-600 px-6 py-4">
                        <h2 class="text-white font-semibold text-lg">Langkah 3: Alamat & Konfirmasi</h2>
                        <p class="text-white/85 text-xs mt-0.5">Lengkapi alamat domisili dan kirimkan pendaftaran</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" rows="3" class="input-base" placeholder="Nama jalan, nomor rumah, RT/RW" required>{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <select name="province" id="province" class="input-base" required>
                                <option value="">— Pilih Provinsi —</option>
                            </select>
                        </div>
                        <div>
                            <label for="district" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select name="district" id="district" class="input-base" disabled required>
                                <option value="">— Pilih Kabupaten —</option>
                            </select>
                        </div>
                        <div>
                            <label for="sub_district" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="sub_district" id="sub_district" class="input-base" disabled required>
                                <option value="">— Pilih Kecamatan —</option>
                            </select>
                        </div>
                        <div>
                            <label for="village" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                            <select name="village" id="village" class="input-base" disabled required>
                                <option value="">— Pilih Kelurahan —</option>
                            </select>
                        </div>

                        {{-- Ringkasan --}}
                        <div class="bg-gray-50 dark:bg-gray-950 rounded-xl p-4 border border-gray-100 dark:border-gray-800 text-sm space-y-2">
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
                    <button type="button" x-show="step < 3" @click="nextStep()"
                            class="flex-1 btn-primary py-3 text-sm font-semibold">
                        Selanjutnya →
                    </button>
                    <button type="submit" x-show="step === 3"
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
<div id="success-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 max-w-md w-full shadow-2xl text-center border border-gray-100 dark:border-gray-800 animate-bounce-in">
        <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Pendaftaran Berhasil!</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Kode Booking Anda</p>
        <p class="text-4xl font-bold text-brand dark:text-brand tracking-widest mb-5">{{ $bk->booking_code }}</p>
        <div class="bg-gray-50 dark:bg-gray-950 rounded-xl p-4 mb-4 text-left text-sm space-y-2 border border-gray-100 dark:border-gray-800">
            <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-800 pb-2 mb-2">
                <span class="text-gray-500 dark:text-gray-400">Nomor Antrean:</span>
                <span class="font-bold text-3xl text-brand dark:text-brand">{{ $bk->queue_number }}</span>
            </div>
            @if($bk->estimated_time)
            <div class="flex justify-between items-center bg-brand/10 dark:bg-brand/20 p-2 rounded-lg">
                <span class="text-brand-dark dark:text-brand font-medium">🕒 Estimasi Dilayani:</span>
                <span class="font-bold text-brand-dark dark:text-brand">Jam {{ $bk->estimated_time }}</span>
            </div>
            @endif
            <p><span class="text-gray-500 dark:text-gray-400">Dokter:</span> <span class="font-medium text-gray-950 dark:text-gray-200">{{ $bk->doctor->name }}</span></p>
            <p><span class="text-gray-500 dark:text-gray-400">Tanggal:</span> <span class="font-medium text-gray-950 dark:text-gray-200">{{ $bk->exam_date->format('d/m/Y') }}</span></p>
            <p><span class="text-gray-500 dark:text-gray-400">Jadwal:</span> <span class="font-medium text-gray-950 dark:text-gray-200">{{ $bk->schedule->day_name }}, {{ $bk->schedule->time_range }}</span></p>
        </div>
        <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 rounded-xl p-3 mb-5 text-xs text-amber-800 dark:text-amber-300 text-left">
            <strong>💡 Tips:</strong> Datanglah mendekati jam estimasi. Pantau antrean melalui menu <strong>Antrean Saya</strong>.
        </div>
        <div class="flex gap-3">
            <a href="{{ route('patient.dashboard') }}" class="btn-primary flex-1 py-3 text-center text-sm">Lihat Antrean</a>
            <button onclick="document.getElementById('success-modal').remove()" class="btn-outline flex-1 py-3 text-sm">Tutup</button>
        </div>
    </div>
</div>
@endif

@push('scripts')
    @vite('resources/js/booking.js')
    <script>
    function bookingWizard() {
        return {
            step: 1,
            doctorId: '{{ old('doctor_id', '') }}',
            examDate: '{{ old('exam_date', '') }}',
            gender: '{{ old('gender', '') }}',
            schedules: [],
            loadingSchedule: false,
            selectedDoctorName: '',
            doctorSearch: '',
            spFilter: '',
            complaint: '{{ old('complaint', '') }}',
            analyzing: false,
            suggestion: null,
            doctorCapacities: {},
            submitting: false,

            init() {
                // If old input exists, jump to correct step
                @if($errors->any())
                    this.step = 1;
                @endif

                if (this.doctorId || this.examDate) {
                    this.loadSchedules();
                }
                if (this.examDate) {
                    this.fetchDoctorCapacities();
                }
            },

            onDoctorChange() {
                const label = document.querySelector(`input[name=doctor_id][value="${this.doctorId}"]`)?.closest('label')?.querySelector('p')?.textContent;
                this.selectedDoctorName = document.querySelector(`input[name=doctor_id][value="${this.doctorId}"]`)?.closest('label')?.querySelector('.font-semibold')?.textContent ?? '';
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
                fetch(`/api/doctor-capacities?date=${this.examDate}`)
                    .then(r => r.json())
                    .then(data => {
                        const capacities = {};
                        data.forEach(item => {
                            capacities[item.doctor_id] = item;
                        });
                        this.doctorCapacities = capacities;
                    });
            },

            analyzeComplaint() {
                if (this.complaint.trim().length < 3) { alert('Silakan tuliskan keluhan Anda terlebih dahulu.'); return; }
                this.analyzing = true;
                this.suggestion = null;

                fetch('/api/suggest-doctor', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ complaint: this.complaint, date: this.examDate || new Date().toISOString().slice(0,10) })
                })
                .then(r => r.json())
                .then(data => {
                    this.analyzing = false;
                    if (data.suggested_doctor) {
                        this.suggestion = data;
                    } else {
                        alert('Maaf, kami tidak menemukan dokter yang spesifik untuk keluhan tersebut.');
                    }
                })
                .catch(() => { this.analyzing = false; alert('Gagal menganalisis keluhan.'); });
            },

            applySuggestion() {
                if (!this.suggestion) return;
                this.doctorId = this.suggestion.suggested_doctor.id;
                this.spFilter = ''; // Reset filter so selected doctor shows
                this.doctorSearch = '';
                
                // If the user hasn't picked a date, set to today
                if (!this.examDate) {
                    this.examDate = new Date().toISOString().slice(0,10);
                }

                this.schedules = this.suggestion.schedules;
                this.selectedDoctorName = this.suggestion.suggested_doctor.name;
                
                // Set schedule if only one
                if (this.schedules.length === 1) {
                    setTimeout(() => {
                        const sel = document.getElementById('select-jadwal');
                        if (sel) sel.value = this.schedules[0].id;
                    }, 50);
                }

                this.suggestion = null;
                // Scroll to schedules
                document.getElementById('select-jadwal').scrollIntoView({ behavior: 'smooth', block: 'center' });
            },

            nextStep() {
                if (this.step === 1) {
                    if (!this.doctorId) { alert('Silakan pilih dokter terlebih dahulu.'); return; }
                    if (!this.examDate) { alert('Silakan pilih tanggal periksa.'); return; }
                    const jadwal = document.getElementById('select-jadwal');
                    if (!jadwal.value) { alert('Silakan pilih jadwal waktu.'); return; }
                }
                if (this.step === 2) {
                    const nik = document.getElementById('nik').value;
                    const name = document.getElementById('patient_name').value;
                    const birth = document.getElementById('birth_date_input').value;
                    const phone = document.getElementById('phone').value;
                    if (!nik || nik.length < 16) { alert('NIK harus 16 digit.'); return; }
                    if (!name) { alert('Nama pasien wajib diisi.'); return; }
                    if (!birth) { alert('Tanggal lahir wajib diisi.'); return; }
                    if (!this.gender) { alert('Jenis kelamin wajib dipilih.'); return; }
                    if (!phone) { alert('Nomor HP wajib diisi.'); return; }
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
