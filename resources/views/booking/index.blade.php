@extends('layouts.app')
@section('title', 'Pendaftaran Online — MyKlinik911')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-teal-50 py-10"
     x-data="bookingWizard()"
     x-init="init()">

    <div class="max-w-2xl mx-auto px-4">

        {{-- Header --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 bg-teal-100 text-teal-700 text-xs font-semibold px-4 py-1.5 rounded-full mb-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Pendaftaran Online
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Buat Janji Temu</h1>
            <p class="text-gray-500 mt-1 text-sm">Selesaikan dalam 3 langkah mudah</p>
        </div>

        {{-- Step Indicator --}}
        <div class="flex items-center justify-center mb-8">
            <template x-for="(label, i) in ['Jadwal', 'Data Pasien', 'Domisili']" :key="i">
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300"
                             :class="step > i+1 ? 'bg-teal-500 text-white' : (step === i+1 ? 'bg-brand text-white shadow-lg scale-110' : 'bg-gray-200 text-gray-400')">
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
                         :class="step > i+1 ? 'bg-teal-400' : 'bg-gray-200'"></div>
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
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <form method="POST" action="{{ route('booking.store') }}" id="booking-form" @submit="handleSubmit">
                @csrf

                {{-- ══ STEP 1: Jadwal & Dokter ══ --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-gradient-to-r from-brand to-teal-600 px-6 py-4">
                        <h2 class="text-white font-semibold text-lg">Langkah 1: Pilih Jadwal & Dokter</h2>
                        <p class="text-white/70 text-xs mt-0.5">Tentukan kapan dan dengan siapa Anda ingin periksa</p>
                    </div>
                    <div class="p-6 space-y-5">
                        {{-- Dokter --}}
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Dokter <span class="text-red-500">*</span></label>

                            {{-- Search & Filter --}}
                            <div class="flex gap-2 mb-3">
                                <div class="relative flex-1">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                                    <input type="text" x-model="doctorSearch" placeholder="Cari nama dokter..."
                                           class="input-base pl-9 text-sm">
                                </div>
                                <select x-model="spFilter" class="input-base text-sm w-44">
                                    <option value="">Semua Spesialis</option>
                                    @foreach($doctors->pluck('specialization_label')->unique()->sort() as $sp)
                                        <option value="{{ $sp }}">{{ $sp }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Doctor Cards (scrollable) --}}
                            <div class="space-y-2 max-h-64 overflow-y-auto pr-1 rounded-xl" id="doctor-cards">
                                @foreach($doctors as $doc)
                                <label class="relative flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all duration-200 hover:border-brand hover:bg-blue-50"
                                       :class="doctorId == '{{ $doc->id }}' ? 'border-brand bg-blue-50 shadow-sm' : 'border-gray-200'"
                                       x-show="(doctorSearch === '' || '{{ strtolower($doc->name) }}'.includes(doctorSearch.toLowerCase())) && (spFilter === '' || spFilter === '{{ $doc->specialization_label }}')"
                                       >
                                    <input type="radio" name="doctor_id" value="{{ $doc->id }}" class="sr-only" x-model="doctorId" @change="onDoctorChange()" {{ old('doctor_id') == $doc->id ? 'checked' : '' }}>
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand to-teal-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                        {{ strtoupper(substr($doc->name, 3, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm leading-tight">{{ $doc->name }}</p>
                                        <p class="text-xs text-teal-600 font-medium mt-0.5">{{ $doc->specialization_label }}</p>
                                    </div>
                                    <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors"
                                         :class="doctorId == '{{ $doc->id }}' ? 'border-brand bg-brand' : 'border-gray-300'">
                                        <div class="w-1.5 h-1.5 rounded-full bg-white" x-show="doctorId == '{{ $doc->id }}'"></div>
                                    </div>
                                </label>
                                @endforeach
                                <p x-show="$el.previousElementSibling && [...$el.parentElement.querySelectorAll('label')].every(l => l.style.display === 'none')"
                                   class="text-center text-sm text-gray-400 py-4">Dokter tidak ditemukan</p>
                            </div>
                        </div>

                        {{-- Tanggal --}}
                        <div>
                            <label for="exam-date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Periksa <span class="text-red-500">*</span></label>
                            <input type="date" name="exam_date" id="exam-date" x-model="examDate" @change="onDateChange()"
                                   class="input-base"
                                   min="{{ now()->addDay()->format('Y-m-d') }}"
                                   max="{{ now()->addDays(14)->format('Y-m-d') }}"
                                   value="{{ old('exam_date') }}" required>
                        </div>

                        {{-- Jadwal --}}
                        <div>
                            <label for="select-jadwal" class="block text-sm font-semibold text-gray-700 mb-2">Jadwal Waktu <span class="text-red-500">*</span></label>
                            <div x-show="loadingSchedule" class="flex items-center gap-2 text-sm text-gray-400 py-3">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                                Memuat jadwal...
                            </div>
                            <select name="schedule_id" id="select-jadwal" class="input-base" :disabled="schedules.length === 0" x-show="!loadingSchedule" required>
                                <option value="">— Pilih jadwal —</option>
                                <template x-for="s in schedules" :key="s.id">
                                    <option :value="s.id" x-text="`${s.day_name} — ${s.start_time} - ${s.end_time} (Maks: ${s.max_patients} pasien)`"></option>
                                </template>
                            </select>
                            <p x-show="!loadingSchedule && schedules.length === 0 && (doctorId && examDate)" class="text-xs text-amber-600 mt-1">Tidak ada jadwal dokter pada hari ini.</p>
                        </div>
                    </div>
                </div>

                {{-- ══ STEP 2: Data Pasien ══ --}}
                <div x-show="step === 2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-4">
                        <h2 class="text-white font-semibold text-lg">Langkah 2: Data Pasien</h2>
                        <p class="text-white/70 text-xs mt-0.5">Isi data diri pasien yang akan diperiksa</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="nik" class="block text-sm font-semibold text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                            <input type="text" name="nik" id="nik" class="input-base" maxlength="16" placeholder="Masukkan 16 digit NIK" value="{{ old('nik') }}" required>
                        </div>
                        <div>
                            <label for="patient_name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Pasien <span class="text-red-500">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" class="input-base" placeholder="Nama lengkap sesuai KTP" value="{{ old('patient_name', Auth::user()->name) }}" required>
                            <p class="text-xs text-gray-400 mt-1">Terisi otomatis dari akun Anda. Ubah jika mendaftarkan orang lain.</p>
                        </div>
                        <div>
                            <label for="birth_date_input" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                            <input type="date" name="birth_date" id="birth_date_input" class="input-base" value="{{ old('birth_date') }}" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                       :class="gender === 'L' ? 'border-brand bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="gender" value="L" class="sr-only" x-model="gender" {{ old('gender') === 'L' ? 'checked' : '' }}>
                                    <span class="text-xl">👨</span>
                                    <span class="font-medium text-sm text-gray-700">Laki-laki</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                       :class="gender === 'P' ? 'border-brand bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                    <input type="radio" name="gender" value="P" class="sr-only" x-model="gender" {{ old('gender') === 'P' ? 'checked' : '' }}>
                                    <span class="text-xl">👩</span>
                                    <span class="font-medium text-sm text-gray-700">Perempuan</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">No HP/WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" id="phone" class="input-base" maxlength="15" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                        </div>
                    </div>
                </div>

                {{-- ══ STEP 3: Domisili & Konfirmasi ══ --}}
                <div x-show="step === 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                        <h2 class="text-white font-semibold text-lg">Langkah 3: Alamat & Konfirmasi</h2>
                        <p class="text-white/70 text-xs mt-0.5">Lengkapi alamat domisili dan kirimkan pendaftaran</p>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea name="address" id="address" rows="3" class="input-base" placeholder="Nama jalan, nomor rumah, RT/RW" required>{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-semibold text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                            <select name="province" id="province" class="input-base" required>
                                <option value="">— Pilih Provinsi —</option>
                            </select>
                        </div>
                        <div>
                            <label for="district" class="block text-sm font-semibold text-gray-700 mb-2">Kabupaten/Kota <span class="text-red-500">*</span></label>
                            <select name="district" id="district" class="input-base" disabled required>
                                <option value="">— Pilih Kabupaten —</option>
                            </select>
                        </div>
                        <div>
                            <label for="sub_district" class="block text-sm font-semibold text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                            <select name="sub_district" id="sub_district" class="input-base" disabled required>
                                <option value="">— Pilih Kecamatan —</option>
                            </select>
                        </div>
                        <div>
                            <label for="village" class="block text-sm font-semibold text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                            <select name="village" id="village" class="input-base" disabled required>
                                <option value="">— Pilih Kelurahan —</option>
                            </select>
                        </div>

                        {{-- Ringkasan --}}
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 text-sm space-y-2">
                            <p class="font-semibold text-gray-700 mb-2">📋 Ringkasan Booking</p>
                            <div class="flex justify-between text-gray-600"><span>Dokter</span><span class="font-medium text-gray-900" x-text="selectedDoctorName || '—'"></span></div>
                            <div class="flex justify-between text-gray-600"><span>Tanggal</span><span class="font-medium text-gray-900" x-text="examDate ? new Date(examDate).toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'}) : '—'"></span></div>
                        </div>
                    </div>
                </div>

                {{-- Navigation Buttons --}}
                <div class="px-6 pb-6 flex gap-3">
                    <button type="button" x-show="step > 1" @click="step--"
                            class="flex-1 py-3 rounded-xl border-2 border-gray-200 text-gray-600 font-semibold hover:bg-gray-50 transition text-sm">
                        ← Sebelumnya
                    </button>
                    <button type="button" x-show="step < 3" @click="nextStep()"
                            class="flex-1 btn-primary py-3 text-sm font-semibold">
                        Selanjutnya →
                    </button>
                    <button type="submit" x-show="step === 3"
                            class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-3 rounded-xl transition text-sm shadow-sm">
                        ✅ Daftar Sekarang
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
    <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl text-center animate-bounce-in">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-1">Pendaftaran Berhasil!</h3>
        <p class="text-sm text-gray-500 mb-4">Kode Booking Anda</p>
        <p class="text-4xl font-bold text-brand tracking-widest mb-5">{{ $bk->booking_code }}</p>
        <div class="bg-gray-50 rounded-xl p-4 mb-4 text-left text-sm space-y-2">
            <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-2">
                <span class="text-gray-500">Nomor Antrean:</span>
                <span class="font-bold text-3xl text-brand">{{ $bk->queue_number }}</span>
            </div>
            @if($bk->estimated_time)
            <div class="flex justify-between items-center bg-blue-50 p-2 rounded-lg">
                <span class="text-blue-800 font-medium">🕒 Estimasi Dilayani:</span>
                <span class="font-bold text-blue-900">Jam {{ $bk->estimated_time }}</span>
            </div>
            @endif
            <p><span class="text-gray-500">Dokter:</span> <span class="font-medium">{{ $bk->doctor->name }}</span></p>
            <p><span class="text-gray-500">Tanggal:</span> <span class="font-medium">{{ $bk->exam_date->format('d/m/Y') }}</span></p>
            <p><span class="text-gray-500">Jadwal:</span> <span class="font-medium">{{ $bk->schedule->day_name }}, {{ $bk->schedule->time_range }}</span></p>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-5 text-xs text-amber-800 text-left">
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

            init() {
                // If old input exists, jump to correct step
                @if($errors->any())
                    this.step = 1;
                @endif

                if (this.doctorId || this.examDate) {
                    this.loadSchedules();
                }
            },

            onDoctorChange() {
                const label = document.querySelector(`input[name=doctor_id][value="${this.doctorId}"]`)?.closest('label')?.querySelector('p')?.textContent;
                this.selectedDoctorName = document.querySelector(`input[name=doctor_id][value="${this.doctorId}"]`)?.closest('label')?.querySelector('.font-semibold')?.textContent ?? '';
                this.loadSchedules();
            },

            onDateChange() {
                this.loadSchedules();
            },

            loadSchedules() {
                if (!this.doctorId || !this.examDate) return;
                this.loadingSchedule = true;
                this.schedules = [];
                const date = new Date(this.examDate);
                const dayOfWeek = date.getDay();
                fetch(`/api/schedules/${this.doctorId}`)
                    .then(r => r.json())
                    .then(data => {
                        this.schedules = data.filter(s => s.day_of_week === dayOfWeek);
                        this.loadingSchedule = false;
                    })
                    .catch(() => { this.loadingSchedule = false; });
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
                // Allow normal form submit
            }
        }
    }
    </script>
@endpush
@endsection
