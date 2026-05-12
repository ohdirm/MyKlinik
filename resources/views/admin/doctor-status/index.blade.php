@extends('layouts.admin')
@section('title', 'Status Dokter — Admin MyKlinik911')
@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">Status Dokter</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($doctors as $doctor)
    @php $st = $doctor->status; $currentStatus = $st->current_status ?? 'AVAILABLE'; @endphp
    <div class="bg-white rounded-xl shadow-sm p-6" id="ds-card-{{ $doctor->id }}">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full bg-brand-light text-brand flex items-center justify-center font-bold shrink-0">{{ $doctor->initials }}</div>
            <div>
                <h3 class="font-semibold text-gray-900">{{ $doctor->name }}</h3>
                <p class="text-xs text-gray-500">{{ $doctor->specialization_label }}</p>
            </div>
        </div>
        <div class="flex flex-wrap gap-1.5 mb-4">
            @foreach(['AVAILABLE'=>'Tersedia','IN_EXAMINATION'=>'Memeriksa','NEXT_AVAILABLE'=>'Segera','UNAVAILABLE'=>'Tidak Tersedia'] as $val=>$label)
                <button onclick="selectStatus({{ $doctor->id }},'{{ $val }}',this)" class="text-xs px-3 py-1.5 rounded-full border transition cursor-pointer {{ $currentStatus === $val ? 'bg-brand text-white border-brand' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}" data-status="{{ $val }}">{{ $label }}</button>
            @endforeach
        </div>
        <div class="{{ $currentStatus === 'IN_EXAMINATION' ? '' : 'hidden' }}" id="queue-input-{{ $doctor->id }}">
            <label class="block text-xs text-gray-500 mb-1">Antrian saat ini</label>
            <input type="number" class="input-base" id="queue-number-{{ $doctor->id }}" value="{{ $st->current_queue_number ?? '' }}" min="1" placeholder="No. antrian">
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
        b.classList.add('bg-white','text-gray-600','border-gray-300');
    });
    btn.classList.remove('bg-white','text-gray-600','border-gray-300');
    btn.classList.add('bg-brand','text-white','border-brand');
    const queueInput = document.getElementById('queue-input-'+doctorId);
    if(status==='IN_EXAMINATION') queueInput.classList.remove('hidden');
    else queueInput.classList.add('hidden');
}

function saveStatus(doctorId) {
    const status = selectedStatuses[doctorId] || document.querySelector('#ds-card-'+doctorId+' [data-status].bg-brand')?.dataset.status || 'AVAILABLE';
    const queueNumber = document.getElementById('queue-number-'+doctorId)?.value || null;
    fetch(`/admin/doctor-status/${doctorId}`, {
        method:'PATCH',
        headers:{'X-CSRF-TOKEN':csrf,'Content-Type':'application/json','Accept':'application/json'},
        body:JSON.stringify({current_status:status,current_queue_number:queueNumber})
    }).then(r=>r.json()).then(data=>{
        if(data.success) showToast('Status berhasil diperbarui!','success');
    }).catch(()=>showToast('Gagal menyimpan.','error'));
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
