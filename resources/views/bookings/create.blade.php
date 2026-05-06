@extends('layouts.app')

@section('title', 'Buat Booking - Klinik App')
@section('header_title', 'Buat Booking Baru')

@section('content')
<div class="max-w-2xl">
    <!-- Step 1: Pilih tanggal dulu -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-6">
        <h4 class="text-lg font-bold text-gray-800 mb-4">Langkah 1: Pilih Tanggal Kunjungan</h4>
        <div>
            <label for="booking_date_picker" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Booking</label>
            <input type="date" id="booking_date_picker" min="{{ date('Y-m-d') }}" value="{{ request('date', old('booking_date')) }}"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
            <p class="text-xs text-gray-500 mt-1">Pilih tanggal terlebih dahulu, sistem akan menampilkan dokter yang tersedia.</p>
        </div>
    </div>

    <!-- Step 2: Pilih jadwal yang tersedia -->
    <div id="schedule-section" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-6 {{ request('date') || old('booking_date') ? '' : 'hidden' }}">
        <h4 class="text-lg font-bold text-gray-800 mb-4">Langkah 2: Pilih Dokter & Jadwal</h4>
        <div id="schedule-loading" class="hidden text-center py-8 text-gray-500">
            <svg class="animate-spin h-8 w-8 mx-auto text-indigo-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            Memuat jadwal...
        </div>
        <div id="schedule-list" class="space-y-3">
            <!-- Akan diisi via JavaScript -->
        </div>
        <div id="schedule-empty" class="hidden text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="font-medium text-gray-700">Tidak ada jadwal pada hari ini</p>
            <p class="text-sm mt-1">Silakan pilih tanggal lain.</p>
        </div>
    </div>

    <!-- Step 3: Form Booking (muncul setelah pilih jadwal) -->
    <div id="booking-form-section" class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 hidden">
        <h4 class="text-lg font-bold text-gray-800 mb-4">Langkah 3: Konfirmasi & Kirim Booking</h4>

        <div id="selected-info" class="bg-indigo-50 rounded-xl p-4 mb-6 text-sm">
            <!-- Akan diisi via JS -->
        </div>

        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="schedule_id" id="form_schedule_id" value="{{ request('schedule_id', old('schedule_id')) }}">
            <input type="hidden" name="booking_date" id="form_booking_date" value="{{ request('date', old('booking_date')) }}">

            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Keluhan / Catatan (Opsional)</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Jelaskan keluhan Anda dengan singkat..."
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            @if($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg border border-red-200 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="flex items-center gap-4">
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
                    Kirim Booking
                </button>
                <a href="{{ route('bookings.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const datePicker = document.getElementById('booking_date_picker');
    const scheduleSection = document.getElementById('schedule-section');
    const scheduleList = document.getElementById('schedule-list');
    const scheduleLoading = document.getElementById('schedule-loading');
    const scheduleEmpty = document.getElementById('schedule-empty');
    const bookingFormSection = document.getElementById('booking-form-section');
    const formScheduleId = document.getElementById('form_schedule_id');
    const formBookingDate = document.getElementById('form_booking_date');
    const selectedInfo = document.getElementById('selected-info');

    let selectedScheduleId = '{{ request("schedule_id", "") }}';

    function loadSchedules(date) {
        if (!date) return;

        scheduleSection.classList.remove('hidden');
        scheduleLoading.classList.remove('hidden');
        scheduleList.innerHTML = '';
        scheduleEmpty.classList.add('hidden');
        bookingFormSection.classList.add('hidden');

        fetch('/api/check-availability?date=' + date)
            .then(r => r.json())
            .then(data => {
                scheduleLoading.classList.add('hidden');

                if (data.length === 0) {
                    scheduleEmpty.classList.remove('hidden');
                    return;
                }

                data.forEach(function(s) {
                    const card = document.createElement('div');
                    const isFull = s.is_full;
                    const isSelected = selectedScheduleId == s.id;

                    card.className = 'flex items-center justify-between p-4 rounded-xl border-2 transition-all cursor-pointer ' +
                        (isFull ? 'border-red-200 bg-red-50/50 opacity-60 cursor-not-allowed' :
                        (isSelected ? 'border-indigo-500 bg-indigo-50 shadow-sm' : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30'));

                    card.innerHTML = `
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold text-gray-900">${s.doctor_name}</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium ${isFull ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}">${isFull ? 'PENUH' : 'TERSEDIA'}</span>
                            </div>
                            <p class="text-sm text-indigo-600">${s.specialization}</p>
                            <p class="text-xs text-gray-500">${s.clinic_name} • ${s.start_time} - ${s.end_time}</p>
                            <p class="text-xs text-gray-400 mt-1">Sisa slot: ${s.available_slots}/${s.max_patients}</p>
                        </div>
                        ${!isFull ? '<div class="ml-4"><svg class="w-6 h-6 ' + (isSelected ? 'text-indigo-600' : 'text-gray-300') + '" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg></div>' : ''}
                    `;

                    if (!isFull) {
                        card.addEventListener('click', function() {
                            selectSchedule(s, date);
                            // Highlight selected
                            document.querySelectorAll('#schedule-list > div').forEach(el => {
                                el.classList.remove('border-indigo-500', 'bg-indigo-50', 'shadow-sm');
                                el.classList.add('border-gray-200');
                            });
                            card.classList.remove('border-gray-200');
                            card.classList.add('border-indigo-500', 'bg-indigo-50', 'shadow-sm');
                            // Update checkmark
                            card.querySelector('svg').classList.remove('text-gray-300');
                            card.querySelector('svg').classList.add('text-indigo-600');
                        });
                    }

                    scheduleList.appendChild(card);

                    // Auto-select if coming from availability page
                    if (isSelected && !isFull) {
                        selectSchedule(s, date);
                    }
                });
            })
            .catch(function() {
                scheduleLoading.classList.add('hidden');
                scheduleList.innerHTML = '<p class="text-red-500 text-center py-4">Gagal memuat data. Silakan coba lagi.</p>';
            });
    }

    function selectSchedule(schedule, date) {
        selectedScheduleId = schedule.id;
        formScheduleId.value = schedule.id;
        formBookingDate.value = date;
        bookingFormSection.classList.remove('hidden');
        selectedInfo.innerHTML = `
            <p class="font-medium text-indigo-800 mb-1">Jadwal yang dipilih:</p>
            <p class="text-indigo-700"><strong>${schedule.doctor_name}</strong> — ${schedule.specialization}</p>
            <p class="text-indigo-600">${schedule.clinic_name} • ${schedule.start_time} - ${schedule.end_time}</p>
            <p class="text-indigo-600">Sisa slot tersedia: ${schedule.available_slots}</p>
        `;
    }

    datePicker.addEventListener('change', function() {
        selectedScheduleId = '';
        loadSchedules(this.value);
    });

    // Load on page load if date is pre-filled
    if (datePicker.value) {
        loadSchedules(datePicker.value);
    }
});
</script>
@endsection
