@extends('layouts.admin')
@section('title', 'Arsip Booking — MyKlinik911')
@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Arsip & Riwayat Booking</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Data pendaftaran yang sudah selesai, dibatalkan, atau kedaluwarsa.</p>
    </div>
</div>

<div x-data="{ showDetail: false, selected: null }">
    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-[#1c2622] border border-[#e2efe7] dark:border-[#283731] rounded-xl shadow-sm p-5 mb-6 transition-colors duration-200">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Cari Pasien / NIK / Kode / Keluhan</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Contoh: gury, 3201..., MK-ABC" class="input-base pl-10 h-10 text-sm">
                    </div>
                </div>
                {{-- Status --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Status</label>
                    <select name="status" class="input-base h-10 text-sm">
                        <option value="">Semua Riwayat</option>
                        @foreach(['DONE', 'REJECTED', 'CANCELLED', 'EXPIRED'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Doctor --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Dokter</label>
                    <select name="doctor_id" class="input-base h-10 text-sm">
                        <option value="">Semua Dokter</option>
                        @foreach($doctors as $d)
                            <option value="{{ $d->id }}" {{ request('doctor_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                {{-- Start Date --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="input-base h-10 text-sm">
                </div>
                {{-- End Date --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="input-base h-10 text-sm">
                </div>
                {{-- Buttons --}}
                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="btn-primary h-10 flex-1 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
                        Terapkan Filter
                    </button>
                    <a href="{{ route('admin.archive.index') }}" class="btn-outline h-10 px-6 flex items-center justify-center">Reset</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-[#1c2622] rounded-xl shadow-sm overflow-hidden border border-[#e2efe7] dark:border-[#283731] transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#F6FBF8] dark:bg-[#141b18] text-[#6B9080] dark:text-[#A8D5BA] text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Pasien</th>
                        <th class="px-4 py-3 text-left">Dokter</th>
                        <th class="px-4 py-3 text-left">Tgl Periksa</th>
                        <th class="px-4 py-3 text-left">Keluhan</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right text-red-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2efe7] dark:divide-[#283731]">
                    @forelse($bookings as $b)
                    <tr class="hover:bg-[#F6FBF8] dark:hover:bg-[#1c2622]/50 transition-colors">
                        <td class="px-4 py-3 font-mono font-bold text-brand dark:text-brand-dark">{{ $b->booking_code }}</td>
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $b->patient_name }}</div>
                            <div class="text-[10px] text-gray-400 font-mono">{{ $b->nik }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-medium">{{ $b->doctor->name }}</div>
                            <div class="text-[10px] text-brand-dark dark:text-brand">{{ $b->doctor->specialization_label }}</div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ $b->exam_date->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">
                            <p class="truncate max-w-[150px] text-xs italic text-gray-500" title="{{ $b->complaint }}">
                                {{ $b->complaint ?: '-' }}
                            </p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $b->status_badge_class }}">
                                {{ $b->status }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="selected = {{ Js::from($b) }}; showDetail = true" class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                                </button>
                                <form action="{{ route('admin.archive.destroy', $b->id) }}" method="POST" onsubmit="return confirm('Pindahkan data ini ke arsip sampah (Soft Delete)?\nData tidak akan muncul lagi di laporan aktif.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Hapus/Arsipkan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.34 12m-4.74 0-.34-12m-8.48-2.4c1.1.2 2.15.42 3.22.61L4.88 20.21a2.25 2.25 0 0 0 2.24 2.11h9.76a2.25 2.25 0 0 0 2.24-2.11l1.12-12.6c1.07-.19 2.12-.41 3.22-.61m-19.4 0 1.95-1.93a2.25 2.25 0 0 1 1.66-.67h5.34a2.25 2.25 0 0 1 1.66.67l1.95 1.93m-19.4 0h19.4"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">Tidak ada riwayat ditemukan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-[#e2efe7] dark:border-[#283731]">{{ $bookings->links() }}</div>
    </div>

    {{-- Detail Modal --}}
    <div x-show="showDetail" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" x-transition style="display: none;">
        <div @click.away="showDetail = false" class="bg-white dark:bg-[#1c2622] border border-[#e2efe7] dark:border-[#283731] rounded-2xl p-6 max-w-2xl w-full shadow-2xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5 border-b border-[#e2efe7] dark:border-[#283731] pb-3">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detail Riwayat Pasien</h3>
                <button @click="showDetail = false" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <template x-if="selected">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div class="space-y-3">
                        <div><span class="text-gray-500 block text-xs">Nama Lengkap</span><strong x-text="selected.patient_name" class="text-base text-gray-900 dark:text-white"></strong></div>
                        <div><span class="text-gray-500 block text-xs">NIK</span><span x-text="selected.nik" class="font-mono"></span></div>
                        <div><span class="text-gray-500 block text-xs">Jenis Kelamin</span><span x-text="selected.gender === 'L' ? 'Laki-laki' : 'Perempuan'"></span></div>
                        <div><span class="text-gray-500 block text-xs">Alamat</span><p x-text="`${selected.address}, ${selected.village}, ${selected.sub_district}`" class="leading-tight"></p></div>
                        <div class="pt-2 border-t border-[#e2efe7] dark:border-[#283731]">
                            <span class="text-gray-500 block text-xs">Keluhan Masuk</span>
                            <p x-text="selected.complaint || '-'" class="italic text-gray-700 dark:text-gray-300"></p>
                        </div>
                    </div>
                    <div class="space-y-3 bg-[#F6FBF8] dark:bg-[#141b18] p-4 rounded-xl border border-[#e2efe7] dark:border-[#283731]">
                        <div><span class="text-gray-500 block text-xs">Kode Booking</span><span x-text="selected.booking_code" class="font-bold text-brand dark:text-brand-dark tracking-wider"></span></div>
                        <div><span class="text-gray-500 block text-xs">Tanggal Periksa</span><span x-text="new Date(selected.exam_date).toLocaleDateString('id-ID', {weekday:'long', year:'numeric', month:'long', day:'numeric'})" class="font-medium"></span></div>
                        <div><span class="text-gray-500 block text-xs">Dokter Pembuat</span><span x-text="selected.doctor?.name" class="font-medium"></span></div>
                        <div>
                            <span class="text-gray-500 block text-xs mb-1">Status Akhir</span>
                            <span x-text="selected.status" class="px-2 py-1 text-[10px] font-bold rounded-full bg-gray-200 dark:bg-gray-800 text-gray-700 dark:text-gray-300"></span>
                        </div>
                        <div x-show="selected.rejection_reason">
                            <span class="text-red-500 block text-xs mb-1">Alasan Penolakan</span>
                            <p x-text="selected.rejection_reason" class="text-xs text-red-600 dark:text-red-400"></p>
                        </div>
                    </div>
                </div>
            </template>
            <div class="mt-6 text-right">
                <button @click="showDetail = false" class="btn-outline px-6">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
