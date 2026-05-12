@extends('layouts.app')
@section('title', 'Pendaftaran Online — MyKlinik911')
@section('content')
<div class="py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-brand font-bold text-2xl text-center mb-6">PENDAFTARAN ONLINE — MYKLINIK911</h1>

        {{-- Info bar --}}
        <div class="bg-teal-500 text-white flex flex-wrap justify-between items-center px-4 py-2.5 text-sm rounded-xl mb-6">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span id="live-clock">Loading...</span>
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                <span>0812-3456-7890</span>
            </div>
        </div>

        {{-- Main card --}}
        <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('booking.store') }}" id="booking-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:divide-x md:divide-gray-200">
                    {{-- LEFT --}}
                    <div>
                        <h2 class="text-brand font-semibold text-lg border-b border-gray-200 pb-2 mb-4">Data Pemeriksaan</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="select-dokter" class="block text-sm font-medium text-gray-700 mb-1">Dokter <span class="text-red-500">*</span></label>
                                <select name="doctor_id" id="select-dokter" class="input-base" required>
                                    <option value="">— Pilih Dokter —</option>
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}" {{ old('doctor_id') == $doc->id ? 'selected' : '' }}>{{ $doc->name }} — {{ $doc->specialization_label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="exam-date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Periksa <span class="text-red-500">*</span></label>
                                <input type="date" name="exam_date" id="exam-date" class="input-base" value="{{ old('exam_date') }}" min="{{ now()->addDay()->format('Y-m-d') }}" max="{{ now()->addDays(14)->format('Y-m-d') }}" required>
                            </div>
                            <div>
                                <label for="select-jadwal" class="block text-sm font-medium text-gray-700 mb-1">Jadwal/Waktu <span class="text-red-500">*</span></label>
                                <select name="schedule_id" id="select-jadwal" class="input-base" disabled required>
                                    <option value="">— Pilih jadwal —</option>
                                </select>
                            </div>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Hari Lahir</label>
                                    <input type="number" id="birth-day" class="input-base" min="1" max="31" placeholder="DD">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                    <input type="number" id="birth-month" class="input-base" min="1" max="12" placeholder="MM">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                    <input type="number" id="birth-year" class="input-base" min="1900" max="{{ date('Y') }}" placeholder="YYYY">
                                </div>
                            </div>
                            <input type="hidden" name="birth_date" id="birth-date-hidden" value="{{ old('birth_date') }}">
                            <div>
                                <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
                                <input type="text" name="nik" id="nik" class="input-base" maxlength="16" placeholder="16 digit NIK" value="{{ old('nik') }}" required>
                            </div>
                            <div>
                                <label for="patient_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien <span class="text-red-500">*</span></label>
                                <input type="text" name="patient_name" id="patient_name" class="input-base" placeholder="Nama lengkap" value="{{ old('patient_name') }}" required>
                            </div>
                            <div>
                                <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="gender" id="gender" class="input-base" required>
                                    <option value="">— Pilih —</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">No HP/WA <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone" class="input-base" maxlength="15" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" required>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT --}}
                    <div class="md:pl-8">
                        <h2 class="text-brand font-semibold text-lg border-b border-gray-200 pb-2 mb-4">Data Domisili</h2>
                        <div class="space-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                                <textarea name="address" id="address" rows="3" class="input-base" placeholder="Alamat lengkap" required>{{ old('address') }}</textarea>
                            </div>
                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                                <select name="province" id="province" class="input-base" required><option value="">— Pilih Provinsi —</option></select>
                            </div>
                            <div>
                                <label for="district" class="block text-sm font-medium text-gray-700 mb-1">Kabupaten/Kota <span class="text-red-500">*</span></label>
                                <select name="district" id="district" class="input-base" disabled required><option value="">— Pilih Kabupaten —</option></select>
                            </div>
                            <div>
                                <label for="sub_district" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                                <select name="sub_district" id="sub_district" class="input-base" disabled required><option value="">— Pilih Kecamatan —</option></select>
                            </div>
                            <div>
                                <label for="village" class="block text-sm font-medium text-gray-700 mb-1">Kelurahan/Desa <span class="text-red-500">*</span></label>
                                <select name="village" id="village" class="input-base" disabled required><option value="">— Pilih Kelurahan —</option></select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 space-y-3">
                    <button type="submit" class="w-full btn-accent py-3 text-base">Daftar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Success Modal --}}
@if(session('booking'))
@php $bk = session('booking'); @endphp
<div id="success-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-xl text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>
        <p class="text-sm text-gray-500 mb-1">Kode Booking</p>
        <p class="text-3xl font-bold text-brand tracking-widest mb-2">{{ $bk->booking_code }}</p>
        
        <div class="bg-gray-50 rounded-xl p-4 mb-4 text-left text-sm space-y-2">
            <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-2">
                <span class="text-gray-500">Nomor Antrean:</span> 
                <span class="font-bold text-3xl text-brand">{{ $bk->queue_number }}</span>
            </div>
            @if($bk->estimated_time)
            <div class="flex justify-between items-center bg-blue-50 p-2 rounded-lg border border-blue-100">
                <span class="text-blue-800 font-medium">🕒 Estimasi Dilayani:</span>
                <span class="font-bold text-blue-900 text-lg">Jam {{ $bk->estimated_time }}</span>
            </div>
            @endif
            <p><span class="text-gray-500">Dokter:</span> <span class="font-medium">{{ $bk->doctor->name }}</span></p>
            <p><span class="text-gray-500">Tanggal:</span> <span class="font-medium">{{ $bk->exam_date->format('d/m/Y') }}</span></p>
            <p><span class="text-gray-500">Jadwal:</span> <span class="font-medium">{{ $bk->schedule->day_name }}, {{ $bk->schedule->time_range }}</span></p>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 mb-6 text-sm text-blue-800 text-left">
            <strong>💡 Panduan Kedatangan:</strong>
            <ul class="list-disc list-inside mt-1 space-y-1">
                <li>Datanglah mendekati jam estimasi di atas.</li>
                <li>Pantau nomor yang sedang dilayani secara <i>real-time</i> melalui menu <strong>Antrean Saya</strong>.</li>
            </ul>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('patient.dashboard') }}" class="btn-primary flex-1 py-3 text-center">Lihat Antrean Saya</a>
            <button onclick="document.getElementById('success-modal').remove()" class="btn-outline flex-1 py-3">Tutup</button>
        </div>
    </div>
</div>
@endif

@push('scripts')
    @vite('resources/js/booking.js')
@endpush
@endsection
