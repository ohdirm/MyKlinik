@extends('layouts.admin')
@section('title', 'Kelola Booking — MyKlinik911')
@section('content')
<h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Kelola Booking</h1>

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
                    <td class="px-4 py-3">{{ $b->doctor->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $b->exam_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $b->queue_number }}</td>
                    <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $b->status_badge_class }}" id="status-{{ $b->id }}">{{ $b->status }}</span></td>
                    <td class="px-4 py-3">
                        <div class="flex gap-1 flex-wrap" id="actions-{{ $b->id }}">
                            <button @click="selected = bookings.find(b => b.id === {{ $b->id }}); showDetail = true" class="text-xs bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 dark:hover:bg-blue-900/50 px-2.5 py-1 rounded-lg transition cursor-pointer">Detail</button>
                            @if($b->status === 'PENDING')
                                <button onclick="confirmBooking({{ $b->id }})" class="text-xs bg-green-500 hover:bg-green-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer">Konfirmasi</button>
                                <button onclick="openRejectModal({{ $b->id }})" class="text-xs bg-red-500 hover:bg-red-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer">Tolak</button>
                            @elseif($b->status === 'CONFIRMED')
                                <button onclick="doneBooking({{ $b->id }})" class="text-xs bg-gray-500 hover:bg-gray-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer">Selesai</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Tidak ada data</td></tr>
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

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

function confirmBooking(id) {
    if (!confirm('Konfirmasi booking ini?')) return;
    fetch(`/admin/bookings/${id}/confirm`, {method:'PATCH',headers:{'X-CSRF-TOKEN':csrf,'Accept':'application/json'}})
        .then(r=>r.json()).then(data=>{
            if(data.success){
                window.open(data.wa_link,'_blank');
                updateRow(id,'CONFIRMED','bg-green-100 text-green-800');
            }
        }).catch(()=>alert('Gagal mengkonfirmasi.'));
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

function updateRow(id, status, badgeClass) {
    const badge = document.getElementById('status-'+id);
    if(badge){badge.textContent=status;badge.className='text-xs font-semibold px-2 py-1 rounded-full '+badgeClass;}
    const actions = document.getElementById('actions-'+id);
    if(actions){
        if(status==='CONFIRMED') actions.innerHTML='<button onclick="doneBooking('+id+')" class="text-xs bg-gray-500 hover:bg-gray-600 text-white px-2.5 py-1 rounded-lg transition cursor-pointer">Selesai</button>';
        else actions.innerHTML='<span class="text-xs text-gray-400">—</span>';
    }
}
</script>
@endpush
@endsection
