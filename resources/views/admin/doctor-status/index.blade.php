@extends('layouts.admin')
@section('title', 'Status Dokter — Admin MyKlinik911')
@section('content')
<h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Status Dokter</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($doctors as $doctor)
    @php $st = $doctor->status; $currentStatus = $st->current_status ?? 'AVAILABLE'; @endphp
    <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm p-6 transition-colors duration-200" id="ds-card-{{ $doctor->id }}">
        <div class="flex items-center gap-3 mb-4">
            @if($doctor->photo)
                <img src="{{ asset('storage/' . $doctor->photo) }}"
                     alt="{{ $doctor->name }}"
                     class="w-12 h-12 rounded-full object-cover shrink-0 border-2 border-brand/20">
            @else
                <div class="w-12 h-12 rounded-full bg-brand-light dark:bg-brand/20 text-brand dark:text-blue-300 flex items-center justify-center font-bold shrink-0">{{ $doctor->initials }}</div>
            @endif
            <div>
                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $doctor->name }}</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $doctor->specialization_label }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-1.5 mb-4">
            @foreach(['AVAILABLE'=>'Tersedia','IN_EXAMINATION'=>'Memeriksa','NEXT_AVAILABLE'=>'Segera','UNAVAILABLE'=>'Tidak Tersedia'] as $val=>$label)
                <button onclick="selectStatus({{ $doctor->id }},'{{ $val }}',this)" class="text-xs px-3 py-1.5 rounded-full border transition cursor-pointer {{ $currentStatus === $val ? 'bg-brand text-white border-brand' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700' }}" data-status="{{ $val }}">{{ $label }}</button>
            @endforeach
        </div>
        <div class="{{ $currentStatus === 'IN_EXAMINATION' ? '' : 'hidden' }}" id="queue-input-{{ $doctor->id }}">
            <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Antrian saat ini</label>
            <input type="number" class="input-base" id="queue-number-{{ $doctor->id }}" value="{{ !empty($st->current_queue_number) ? $st->current_queue_number : '' }}" min="1" placeholder="No. antrian">
        </div>
        <button onclick="saveStatus({{ $doctor->id }})" class="btn-primary w-full mt-3 text-sm py-2">Simpan</button>
    </div>
    @endforeach
</div>

{{-- Toast container --}}
<div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>

@push('scripts')
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const selectedStatuses = {};

function selectStatus(doctorId, status, btn) {
    selectedStatuses[doctorId] = status;
    const card = document.getElementById('ds-card-'+doctorId);
    card.querySelectorAll('[data-status]').forEach(b=>{
        b.classList.remove('bg-brand','text-white','border-brand');
        b.classList.add('bg-white','dark:bg-gray-800','text-gray-600','dark:text-gray-300','border-gray-300','dark:border-gray-700');
    });
    btn.classList.remove('bg-white','dark:bg-gray-800','text-gray-600','dark:text-gray-300','border-gray-300','dark:border-gray-700');
    btn.classList.add('bg-brand','text-white','border-brand');
    const queueInput = document.getElementById('queue-input-'+doctorId);
    if(status==='IN_EXAMINATION') queueInput.classList.remove('hidden');
    else queueInput.classList.add('hidden');
}

function saveStatus(doctorId) {
    const status = selectedStatuses[doctorId] || document.querySelector('#ds-card-'+doctorId+' [data-status].bg-brand')?.dataset.status || 'AVAILABLE';
    let queueVal = document.getElementById('queue-number-'+doctorId)?.value;
    const queueNumber = (queueVal && queueVal !== "0") ? queueVal : null;
    fetch(`/admin/doctor-status/${doctorId}`, {
        method:'PATCH',
        headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json','Accept':'application/json'},
        body:JSON.stringify({current_status:status,current_queue_number:queueNumber})
    }).then(async r=>{
        const data = await r.json();
        if(r.ok && data.success) {
            showToast('Status berhasil diperbarui!','success');
        } else {
            const errorMsg = data.message || 'Gagal menyimpan.';
            showToast(errorMsg, 'error');
        }
    }).catch(()=>showToast('Gagal terhubung ke server.','error'));
}

function showToast(msg, type) {
    const c = document.getElementById('toast-container');
    const t = document.createElement('div');
    t.className = 'px-4 py-3 rounded-xl shadow-lg text-sm font-medium transition-all '+(type==='success'?'bg-green-500 text-white':'bg-red-500 text-white');
    t.textContent = msg;
    c.appendChild(t);
    setTimeout(()=>{t.style.opacity='0';setTimeout(()=>t.remove(),300);},2000);
}
</script>
@endpush
@endsection
