@extends('layouts.admin')
@section('title', 'Kelola Booking — MyKlinik911')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Booking</h1>
    <button onclick="openWalkinModal()" class="btn-primary inline-flex items-center gap-2 cursor-pointer">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Tambah Walk-in
    </button>
</div>

<div x-data="{ showDetail: false, selected: null, bookings: {{ Js::from($bookings->items()) }} }">
{{-- Filter bar --}}
<div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-4 mb-6 transition-colors duration-200">
    <form method="GET" class="flex gap-4 flex-wrap items-end">
        <div>
            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Tanggal</label>
            <input type="date" name="date" value="{{ request('date') }}" class="input-base">
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Dokter</label>
            <select name="doctor_id" class="input-base">
                <option value="">Semua</option>
                @foreach($doctors as $d)
                    <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Status</label>
            <select name="status" class="input-base">
                <option value="">Semua</option>
                @foreach(['PENDING','CONFIRMED','REJECTED','DONE','CANCELLED'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">Filter</button>
        <a href="{{ route('admin.bookings.index') }}" class="btn-outline">Reset</a>
    </form>
</div>

{{-- Table --}}
<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800 transition-colors duration-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-950 text-gray-600 dark:text-gray-400 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">NIK</th>
                    <th class="px-4 py-3 text-left">Dokter</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Antrian</th>
                    <th class="px-4 py-3 text-left">Sumber</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($bookings as $b)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50" id="row-{{ $b->id }}">
                    <td class="px-4 py-3 font-mono font-semibold text-brand dark:text-blue-400">{{ $b->booking_code }}</td>
                    <td class="px-4 py-3">{{ $b->patient_name }}</td>
                    <td class="px-4 py-3 font-mono text-xs">{{ $b->nik }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand to-teal-500 flex items-center justify-center text-white font-bold text-[10px] shrink-0 shadow-sm overflow-hidden">
                                @if($b->doctor->photo_url)
                                    <img src="{{ $b->doctor->photo_url }}" alt="{{ $b->doctor->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ $b->doctor->initials ?? '-' }}
                                @endif
                            </div>
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $b->doctor->name ?? '-' }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $b->exam_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $b->queue_number }}</td>
                    <td class="px-4 py-3">
                        @if($b->booking_source === 'WALK_IN')
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">Walk-in</span>
                        @else
                            <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">Online</span>
                        @endif
                    </td>
                    <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $b->status_badge_class }}" id="status-{{ $b->id }}">{{ $b->status }}</span></td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1 flex-wrap" id="actions-{{ $b->id }}">
                            <button @click="selected = bookings.find(b => b.id === {{ $b->id }}); showDetail = true" class="text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/50 px-2.5 py-1 rounded-lg transition cursor-pointer">Detail</button>
                            @if($b->status === 'PENDING')
                                <button onclick="confirmBooking({{ $b->id }}, this)" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer">Konfirmasi</button>
                                <button onclick="openRejectModal({{ $b->id }})" class="text-xs bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer">Tolak</button>
                            @elseif($b->status === 'CONFIRMED')
                                <a href="{{ $b->whatsapp_link }}" target="_blank" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp
                                </a>
                                <button onclick="doneBooking({{ $b->id }})" class="text-xs bg-gray-500 hover:bg-gray-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer">Selesai</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">{{ $bookings->withQueryString()->links() }}</div>
</div>

{{-- Detail Modal (Alpine) --}}
<div x-show="showDetail" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" x-transition style="display: none;">
    <div @click.away="showDetail = false" class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-5 border-b border-gray-100 dark:border-gray-800 pb-3">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Pasien & Booking</h3>
            <button @click="showDetail = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <template x-if="selected">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                {{-- Kiri --}}
                <div class="space-y-3">
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">Nama Pasien</span><strong x-text="selected.patient_name" class="text-base text-gray-900 dark:text-white"></strong></div>
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">NIK</span><span x-text="selected.nik" class="font-mono text-gray-800 dark:text-gray-200"></span></div>
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">Jenis Kelamin</span><span x-text="selected.gender === 'L' ? 'Laki-laki' : 'Perempuan'" class="text-gray-800 dark:text-gray-200"></span></div>
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">Tanggal Lahir</span><span x-text="new Date(selected.birth_date).toLocaleDateString('id-ID')" class="text-gray-800 dark:text-gray-200"></span></div>
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">No. HP/WA</span><span x-text="selected.phone" class="font-mono text-gray-800 dark:text-gray-200"></span></div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-xs">Alamat Lengkap</span>
                        <p class="text-gray-800 dark:text-gray-200 leading-relaxed">
                            <span x-text="selected.address"></span>,
                            <span x-text="selected.village"></span>,
                            <span x-text="selected.sub_district"></span>,
                            <span x-text="selected.district"></span>,
                            <span x-text="selected.province"></span>
                        </p>
                    </div>
                    <div x-show="selected.complaint">
                        <span class="text-gray-500 dark:text-gray-400 block text-xs">Keluhan Penyakit</span>
                        <p x-text="selected.complaint" class="text-gray-800 dark:text-gray-200 italic"></p>
                    </div>
                </div>
                {{-- Kanan --}}
                <div class="space-y-3 bg-gray-50 dark:bg-gray-950 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">Kode Booking</span><span x-text="selected.booking_code" class="font-bold text-brand dark:text-blue-400 tracking-widest text-lg"></span></div>
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">Nomor Antrean</span><span x-text="selected.queue_number" class="font-bold text-xl text-gray-900 dark:text-white"></span></div>
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">Tanggal Periksa</span><span x-text="new Date(selected.exam_date).toLocaleDateString('id-ID', {weekday:'long', year:'numeric', month:'long', day:'numeric'})" class="text-gray-800 dark:text-gray-200 font-medium"></span></div>
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">Dokter</span><span x-text="selected.doctor?.name" class="text-gray-800 dark:text-gray-200 font-medium"></span></div>
                    <div><span class="text-gray-500 dark:text-gray-400 block text-xs">Jadwal Praktik</span><span x-text="`${selected.schedule?.day_name}, ${selected.schedule?.start_time.slice(0,5)} - ${selected.schedule?.end_time.slice(0,5)}`" class="text-gray-800 dark:text-gray-200"></span></div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-xs mb-1">Status</span>
                        <span x-text="selected.status" class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200"></span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400 block text-xs mb-1">Sumber</span>
                        <span x-text="selected.booking_source === 'WALK_IN' ? 'Walk-in' : 'Online'" class="px-2 py-1 text-xs font-semibold rounded-full" :class="selected.booking_source === 'WALK_IN' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'"></span>
                    </div>
                </div>
            </div>
        </template>
        <div class="mt-6 text-right">
            <button @click="showDetail = false" class="btn-outline px-6">Tutup</button>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div id="reject-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 max-w-md w-full shadow-xl">
        <h3 class="font-bold text-gray-900 dark:text-white mb-4">Alasan Penolakan</h3>
        <textarea id="reject-reason" class="input-base mb-1" rows="3" placeholder="Tuliskan alasan penolakan (minimal 10 karakter)"></textarea>
        <p id="reject-error" class="text-red-500 text-xs mb-3 hidden">Alasan harus minimal 10 karakter.</p>
        <input type="hidden" id="reject-booking-id">
        <div class="flex gap-3">
            <button onclick="submitReject()" class="btn-primary flex-1 bg-red-600 hover:bg-red-700">Tolak Booking</button>
            <button onclick="document.getElementById('reject-modal').classList.add('hidden')" class="btn-outline flex-1">Batal</button>
        </div>
    </div>
</div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- WALK-IN REGISTRATION MODAL                                    --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div id="walkin-modal" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display:none;">
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[92vh] overflow-y-auto">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-600 to-brand px-6 py-4 rounded-t-2xl flex items-center justify-between">
            <div>
                <h2 class="text-white font-bold text-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z"/></svg>
                    Registrasi Walk-in
                </h2>
                <p class="text-white/70 text-xs mt-0.5">Daftarkan pasien yang datang langsung ke klinik</p>
            </div>
            <button onclick="closeWalkinModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6 space-y-6" id="walkin-form-content">
            {{-- ── Keluhan Penyakit & Auto-suggest ── --}}
            <div class="bg-purple-50 dark:bg-purple-950/30 border border-purple-200 dark:border-purple-800 rounded-xl p-4">
                <label class="block text-sm font-semibold text-purple-800 dark:text-purple-200 mb-2">
                    🩺 Keluhan Penyakit Pasien
                </label>
                <textarea id="wk-complaint" class="input-base" rows="2" placeholder="Contoh: sakit perut, mual, demam tinggi sudah 3 hari..."></textarea>
                <div class="flex items-center gap-2 mt-2">
                    <button type="button" onclick="analyzeComplaint()" class="text-xs bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-lg transition cursor-pointer inline-flex items-center gap-1.5 font-medium">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        Analisis & Cari Dokter
                    </button>
                    <span id="wk-suggest-loading" class="hidden text-xs text-gray-400 inline-flex items-center gap-1">
                        <svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>
                        Menganalisis...
                    </span>
                </div>

                {{-- Suggestion result --}}
                <div id="wk-suggestion-result" class="hidden mt-3 p-3 rounded-lg border text-sm space-y-1">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- ── Kolom Kiri: Dokter & Jadwal ── --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                        Jadwal & Dokter
                    </h3>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Dokter <span class="text-red-500">*</span></label>
                        <select id="wk-doctor" class="input-base" onchange="loadWalkinSchedules()">
                            <option value="">— Pilih Dokter —</option>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}">{{ $doc->name }} — {{ $doc->specialization_label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tanggal Periksa <span class="text-red-500">*</span></label>
                        <input type="date" id="wk-exam-date" class="input-base" value="{{ now()->format('Y-m-d') }}" min="{{ now()->format('Y-m-d') }}" onchange="loadWalkinSchedules()">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Jadwal <span class="text-red-500">*</span></label>
                        <select id="wk-schedule" class="input-base">
                            <option value="">— Pilih dokter & tanggal dulu —</option>
                        </select>
                        <p id="wk-schedule-empty" class="hidden text-xs text-amber-600 mt-1">Tidak ada jadwal dokter pada hari ini.</p>
                    </div>
                </div>

                {{-- ── Kolom Kanan: Data Pasien ── --}}
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                        Data Pasien
                    </h3>

                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Nama Pasien <span class="text-red-500">*</span></label>
                        <input type="text" id="wk-name" class="input-base" placeholder="Nama lengkap sesuai KTP">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">NIK <span class="text-red-500">*</span></label>
                        <input type="text" id="wk-nik" class="input-base" maxlength="16" placeholder="16 digit NIK">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">No HP/WA <span class="text-red-500">*</span></label>
                            <input type="text" id="wk-phone" class="input-base" maxlength="15" placeholder="08xxx">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                            <select id="wk-gender" class="input-base">
                                <option value="">— Pilih —</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" id="wk-birth-date" class="input-base">
                    </div>
                </div>
            </div>

            {{-- ── Alamat ── --}}
            <div class="space-y-4">
                <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/></svg>
                    Alamat Pasien
                </h3>

                <div>
                    <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea id="wk-address" class="input-base" rows="2" placeholder="Jalan, nomor rumah, RT/RW"></textarea>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Provinsi <span class="text-red-500">*</span></label>
                        <select id="wk-province" class="input-base" onchange="loadWkDistricts()">
                            <option value="">— Pilih —</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Kab/Kota <span class="text-red-500">*</span></label>
                        <select id="wk-district" class="input-base" disabled onchange="loadWkSubDistricts()">
                            <option value="">— Pilih —</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Kecamatan <span class="text-red-500">*</span></label>
                        <select id="wk-sub-district" class="input-base" disabled onchange="loadWkVillages()">
                            <option value="">— Pilih —</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Kelurahan <span class="text-red-500">*</span></label>
                        <select id="wk-village" class="input-base" disabled>
                            <option value="">— Pilih —</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Error --}}
            <div id="wk-error" class="hidden bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm px-4 py-3 rounded-xl"></div>

            {{-- Submit --}}
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="submitWalkin()" id="wk-submit-btn" class="flex-1 bg-gradient-to-r from-purple-600 to-brand hover:from-purple-700 hover:to-blue-700 text-white font-semibold py-3 rounded-xl transition text-sm shadow-sm cursor-pointer inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                    Daftarkan Pasien
                </button>
                <button type="button" onclick="closeWalkinModal()" class="btn-outline py-3 px-6">Batal</button>
            </div>
        </div>

        {{-- ── Success Panel (hidden by default) ── --}}
        <div id="wk-success" class="hidden p-8 text-center">
            <div class="w-20 h-20 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-1">Walk-in Berhasil Terdaftar!</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Kode Booking</p>
            <p id="wk-success-code" class="text-4xl font-bold text-brand dark:text-blue-400 tracking-widest mb-2"></p>
            <div class="bg-gray-50 dark:bg-gray-950 rounded-xl p-4 mb-4 text-left text-sm space-y-2 border border-gray-100 dark:border-gray-800 max-w-sm mx-auto">
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-800 pb-2 mb-2">
                    <span class="text-gray-500 dark:text-gray-400">Nomor Antrean:</span>
                    <span id="wk-success-queue" class="font-bold text-3xl text-brand dark:text-blue-400"></span>
                </div>
                <p><span class="text-gray-500 dark:text-gray-400">Dokter:</span> <span id="wk-success-doctor" class="font-medium text-gray-900 dark:text-gray-200"></span></p>
                <p><span class="text-gray-500 dark:text-gray-400">Pasien:</span> <span id="wk-success-patient" class="font-medium text-gray-900 dark:text-gray-200"></span></p>
            </div>
            <div class="flex gap-3 max-w-sm mx-auto">
                <a id="wk-success-wa" href="#" target="_blank" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-3 rounded-xl transition text-sm text-center inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Kirim WA
                </a>
                <button onclick="closeWalkinModal(); location.reload();" class="flex-1 btn-outline py-3">Tutup</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// ═══════════════════════════════════════════════════
// EXISTING BOOKING MANAGEMENT
// ═══════════════════════════════════════════════════
function confirmBooking(id, btn) {
    if (!confirm('Konfirmasi booking ini?')) return;
    
    // Loading state
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>';

    fetch(`/admin/bookings/${id}/confirm`, {method:'PATCH',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}})
        .then(r=>r.json()).then(data=>{
            if(data.success){
                const emailStatus = data.email_sent ? 'Email Berhasil Terkirim' : 'Email Gagal Terkirim (Cek .env)';
                alert(`Konfirmasi Berhasil!\n🚀 ${emailStatus}\n\nSetelah ini WhatsApp akan terbuka.`);
                
                window.open(data.wa_link,'_blank');
                updateRow(id,'CONFIRMED','bg-green-100 text-green-800', data.wa_link);
            }
        })
        .catch(()=> {
            alert('Gagal mengkonfirmasi.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
}

function openRejectModal(id) {
    document.getElementById('reject-booking-id').value = id;
    document.getElementById('reject-reason').value = '';
    document.getElementById('reject-error').classList.add('hidden');
    document.getElementById('reject-modal').classList.remove('hidden');
}

function submitReject() {
    const id = document.getElementById('reject-booking-id').value;
    const reason = document.getElementById('reject-reason').value;
    if (reason.length < 10) { document.getElementById('reject-error').classList.remove('hidden'); return; }
    fetch(`/admin/bookings/${id}/reject`, {method:'PATCH',headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify({rejection_reason:reason})})
        .then(r=>r.json()).then(data=>{
            if(data.success){
                document.getElementById('reject-modal').classList.add('hidden');
                updateRow(id,'REJECTED','bg-red-100 text-red-800');
            }
        }).catch(()=>alert('Gagal menolak.'));
}

function doneBooking(id) {
    if (!confirm('Tandai booking ini selesai?')) return;
    fetch(`/admin/bookings/${id}/done`, {method:'PATCH',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}})
        .then(r=>r.json()).then(data=>{
            if(data.success) updateRow(id,'DONE','bg-gray-100 text-gray-800');
        }).catch(()=>alert('Gagal.'));
}

function updateRow(id, status, badgeClass, waLink = null) {
    const badge = document.getElementById('status-'+id);
    if(badge){badge.textContent=status;badge.className='text-xs font-semibold px-2 py-1 rounded-full '+badgeClass;}
    const actions = document.getElementById('actions-'+id);
    if(actions){
        if(status==='CONFIRMED') {
            actions.innerHTML = `
                <a href="${waLink}" target="_blank" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer inline-flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    WhatsApp
                </a>
                <button onclick="doneBooking(${id})" class="text-xs bg-gray-500 hover:bg-gray-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer">Selesai</button>
            `;
        }
        else actions.innerHTML='<span class="text-xs text-gray-400">—</span>';
    }
}

// ═══════════════════════════════════════════════════
// WALK-IN REGISTRATION
// ═══════════════════════════════════════════════════
function openWalkinModal() {
    document.getElementById('walkin-modal').style.display = 'flex';
    document.getElementById('walkin-modal').classList.remove('hidden');
    document.getElementById('wk-form-content')?.style && (document.getElementById('walkin-form-content').style.display = '');
    document.getElementById('walkin-form-content').style.display = '';
    document.getElementById('wk-success').classList.add('hidden');
    document.getElementById('wk-error').classList.add('hidden');
    // Load provinces
    loadWkProvinces();
}

function closeWalkinModal() {
    document.getElementById('walkin-modal').style.display = 'none';
    // Reset form fields
    ['wk-name','wk-nik','wk-phone','wk-birth-date','wk-address','wk-complaint'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    ['wk-doctor','wk-gender'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.selectedIndex = 0;
    });
    document.getElementById('wk-exam-date').value = new Date().toISOString().slice(0,10);
    document.getElementById('wk-suggestion-result').classList.add('hidden');
}

// ── Complaint Analysis / Doctor Suggestion ──
function analyzeComplaint() {
    const complaint = document.getElementById('wk-complaint').value.trim();
    if (complaint.length < 3) { alert('Tuliskan keluhan penyakit pasien terlebih dahulu.'); return; }

    const loading = document.getElementById('wk-suggest-loading');
    const result = document.getElementById('wk-suggestion-result');
    loading.classList.remove('hidden');
    result.classList.add('hidden');

    fetch('/api/suggest-doctor', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify({ complaint })
    })
    .then(r => r.json())
    .then(data => {
        loading.classList.add('hidden');
        result.classList.remove('hidden');

        if (!data.suggested_doctor) {
            result.className = 'mt-3 p-3 rounded-lg border text-sm space-y-1 bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200';
            result.innerHTML = '<p class="font-medium">⚠️ Tidak ada dokter yang tersedia hari ini untuk keluhan ini.</p><p class="text-xs">Silakan pilih dokter secara manual.</p>';
            return;
        }

        let html = '';
        if (data.fallback) {
            result.className = 'mt-3 p-3 rounded-lg border text-sm space-y-1 bg-amber-50 dark:bg-amber-950/30 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200';
            html += `<p class="font-medium">⚠️ ${data.fallback_reason}</p>`;
        } else {
            result.className = 'mt-3 p-3 rounded-lg border text-sm space-y-1 bg-green-50 dark:bg-green-950/30 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200';
        }

        html += `<p class="font-medium">✅ Rekomendasi: <strong>${data.suggested_doctor.name}</strong> — ${data.suggested_doctor.specialization_label}</p>`;
        if (data.matched_specialization) {
            html += `<p class="text-xs opacity-80">Keluhan cocok dengan: ${data.matched_specialization.label} (skor kecocokan: ${data.score})</p>`;
        }
        html += `<button type="button" onclick="applySuggestion(${data.suggested_doctor.id})" class="mt-3 w-full bg-white dark:bg-gray-800 text-current border border-current px-4 py-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 cursor-pointer text-xs font-bold shadow-sm flex items-center justify-center gap-2">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
            Gunakan Rekomendasi Ini & Isi Jadwal
        </button>`;

        result.innerHTML = html;

        // Store schedules in a global for the apply function
        window._suggestedSchedules = data.schedules;
    })
    .catch(() => {
        loading.classList.add('hidden');
        alert('Gagal menganalisis keluhan.');
    });
}

function applySuggestion(doctorId) {
    // Set doctor select
    const doctorSelect = document.getElementById('wk-doctor');
    doctorSelect.value = doctorId;

    // Set date to today
    document.getElementById('wk-exam-date').value = new Date().toISOString().slice(0,10);

    // Populate schedules
    const scheduleSelect = document.getElementById('wk-schedule');
    const schedules = window._suggestedSchedules || [];
    scheduleSelect.innerHTML = '<option value="">— Pilih jadwal —</option>';
    schedules.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = `${s.day_name} — ${s.start_time} - ${s.end_time} (Maks: ${s.max_patients} pasien)`;
        scheduleSelect.appendChild(opt);
    });

    if (schedules.length === 1) {
        scheduleSelect.selectedIndex = 1;
    }

    document.getElementById('wk-schedule-empty').classList.toggle('hidden', schedules.length > 0);

    // Scroll to doctor field
    doctorSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// ── Schedule loading for Walk-in ──
function loadWalkinSchedules() {
    const doctorId = document.getElementById('wk-doctor').value;
    const examDate = document.getElementById('wk-exam-date').value;
    const scheduleSelect = document.getElementById('wk-schedule');
    const emptyMsg = document.getElementById('wk-schedule-empty');

    if (!doctorId || !examDate) {
        scheduleSelect.innerHTML = '<option value="">— Pilih dokter & tanggal dulu —</option>';
        emptyMsg.classList.add('hidden');
        return;
    }

    const dayOfWeek = new Date(examDate).getDay();

    fetch(`/api/schedules/${doctorId}`)
        .then(r => r.json())
        .then(data => {
            const filtered = data.filter(s => s.day_of_week === dayOfWeek);
            scheduleSelect.innerHTML = '<option value="">— Pilih jadwal —</option>';
            filtered.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = `${s.day_name} — ${s.start_time} - ${s.end_time} (Maks: ${s.max_patients} pasien)`;
                scheduleSelect.appendChild(opt);
            });
            if (filtered.length === 1) scheduleSelect.selectedIndex = 1;
            emptyMsg.classList.toggle('hidden', filtered.length > 0);
        });
}

// ── Wilayah cascading for Walk-in ──
function loadWkProvinces() {
    fetch('/api/wilayah/provinces')
        .then(r => r.json())
        .then(data => {
            const select = document.getElementById('wk-province');
            select.innerHTML = '<option value="">— Pilih —</option>';
            data.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.name;
                opt.textContent = p.name;
                opt.dataset.id = p.id;
                select.appendChild(opt);
            });
        });
}

function loadWkDistricts() {
    const select = document.getElementById('wk-province');
    const selectedOpt = select.options[select.selectedIndex];
    const provId = selectedOpt?.dataset?.id;
    const distSelect = document.getElementById('wk-district');
    const subSelect = document.getElementById('wk-sub-district');
    const vilSelect = document.getElementById('wk-village');

    distSelect.innerHTML = '<option value="">— Pilih —</option>';
    distSelect.disabled = true;
    subSelect.innerHTML = '<option value="">— Pilih —</option>';
    subSelect.disabled = true;
    vilSelect.innerHTML = '<option value="">— Pilih —</option>';
    vilSelect.disabled = true;

    if (!provId) return;

    fetch(`/api/wilayah/districts?province_id=${provId}`)
        .then(r => r.json())
        .then(data => {
            distSelect.innerHTML = '<option value="">— Pilih —</option>';
            data.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.name;
                opt.textContent = d.name;
                opt.dataset.id = d.id;
                distSelect.appendChild(opt);
            });
            distSelect.disabled = false;
        });
}

function loadWkSubDistricts() {
    const select = document.getElementById('wk-district');
    const selectedOpt = select.options[select.selectedIndex];
    const distId = selectedOpt?.dataset?.id;
    const subSelect = document.getElementById('wk-sub-district');
    const vilSelect = document.getElementById('wk-village');

    subSelect.innerHTML = '<option value="">— Pilih —</option>';
    subSelect.disabled = true;
    vilSelect.innerHTML = '<option value="">— Pilih —</option>';
    vilSelect.disabled = true;

    if (!distId) return;

    fetch(`/api/wilayah/subdistricts?district_id=${distId}`)
        .then(r => r.json())
        .then(data => {
            subSelect.innerHTML = '<option value="">— Pilih —</option>';
            data.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.name;
                opt.textContent = d.name;
                opt.dataset.id = d.id;
                subSelect.appendChild(opt);
            });
            subSelect.disabled = false;
        });
}

function loadWkVillages() {
    const select = document.getElementById('wk-sub-district');
    const selectedOpt = select.options[select.selectedIndex];
    const subId = selectedOpt?.dataset?.id;
    const vilSelect = document.getElementById('wk-village');

    vilSelect.innerHTML = '<option value="">— Pilih —</option>';
    vilSelect.disabled = true;

    if (!subId) return;

    fetch(`/api/wilayah/villages?sub_district_id=${subId}`)
        .then(r => r.json())
        .then(data => {
            vilSelect.innerHTML = '<option value="">— Pilih —</option>';
            data.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.name;
                opt.textContent = d.name;
                vilSelect.appendChild(opt);
            });
            vilSelect.disabled = false;
        });
}

// ── Submit Walk-in ──
function submitWalkin() {
    const errorEl = document.getElementById('wk-error');
    errorEl.classList.add('hidden');

    // Gather form data
    const data = {
        patient_name: document.getElementById('wk-name').value.trim(),
        nik: document.getElementById('wk-nik').value.trim(),
        phone: document.getElementById('wk-phone').value.trim(),
        birth_date: document.getElementById('wk-birth-date').value,
        gender: document.getElementById('wk-gender').value,
        doctor_id: document.getElementById('wk-doctor').value,
        schedule_id: document.getElementById('wk-schedule').value,
        exam_date: document.getElementById('wk-exam-date').value,
        address: document.getElementById('wk-address').value.trim(),
        province: document.getElementById('wk-province').value,
        district: document.getElementById('wk-district').value,
        sub_district: document.getElementById('wk-sub-district').value,
        village: document.getElementById('wk-village').value,
        complaint: document.getElementById('wk-complaint').value.trim(),
    };

    // Basic client-side validation
    const required = ['patient_name','nik','phone','birth_date','gender','doctor_id','schedule_id','exam_date','address','province','district','sub_district','village'];
    const missing = required.filter(k => !data[k]);
    if (missing.length > 0) {
        errorEl.textContent = 'Semua field bertanda * wajib diisi.';
        errorEl.classList.remove('hidden');
        return;
    }
    if (data.nik.length !== 16) {
        errorEl.textContent = 'NIK harus tepat 16 digit.';
        errorEl.classList.remove('hidden');
        return;
    }

    // Disable button
    const btn = document.getElementById('wk-submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg> Mendaftarkan...';

    fetch('/admin/bookings', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data: resp }) => {
        if (!ok || !resp.success) {
            errorEl.textContent = resp.message || Object.values(resp.errors || {}).flat().join(', ') || 'Terjadi kesalahan.';
            errorEl.classList.remove('hidden');
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Daftarkan Pasien';
            return;
        }

        // Show success
        document.getElementById('walkin-form-content').style.display = 'none';
        document.getElementById('wk-success').classList.remove('hidden');
        document.getElementById('wk-success-code').textContent = resp.booking.booking_code;
        document.getElementById('wk-success-queue').textContent = resp.booking.queue_number;
        document.getElementById('wk-success-doctor').textContent = resp.booking.doctor?.name || '-';
        document.getElementById('wk-success-patient').textContent = resp.booking.patient_name;
        document.getElementById('wk-success-wa').href = resp.wa_link;
    })
    .catch(() => {
        errorEl.textContent = 'Terjadi kesalahan koneksi.';
        errorEl.classList.remove('hidden');
        btn.disabled = false;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg> Daftarkan Pasien';
    });
}
</script>
@endpush
@endsection
