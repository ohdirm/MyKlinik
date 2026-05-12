@extends('layouts.app')
@section('title', 'Status Dokter — MyKlinik911')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Status Dokter</h1>
            <p class="text-gray-500">Pantau ketersediaan dokter secara real-time</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="doctor-status-grid">
            @foreach($doctors as $doctor)
            @php $st = $doctor->status; @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition" id="doctor-card-{{ $doctor->id }}">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-14 h-14 rounded-full bg-brand-light text-brand flex items-center justify-center font-bold text-lg shrink-0">{{ $doctor->initials }}</div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $doctor->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $doctor->specialization_label }}</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full {{ $st ? $st->status_badge_class : 'bg-green-100 text-green-800' }}" id="badge-{{ $doctor->id }}">
                    <span class="w-2 h-2 rounded-full {{ $st && $st->current_status === 'AVAILABLE' ? 'bg-green-500' : ($st && $st->current_status === 'IN_EXAMINATION' ? 'bg-yellow-500 animate-pulse' : ($st && $st->current_status === 'UNAVAILABLE' ? 'bg-red-500' : 'bg-blue-500')) }}"></span>
                    {{ $st ? $st->status_label : 'Tersedia' }}
                    @if($st && $st->current_status === 'IN_EXAMINATION' && $st->current_queue_number)
                        — Antrian No. {{ $st->current_queue_number }}
                    @endif
                </span>
            </div>
            @endforeach
        </div>
        <p class="text-center text-xs text-gray-400 mt-8">Status diperbarui otomatis setiap 30 detik</p>
    </div>
</div>
@push('scripts')
<script>
setInterval(function(){
    fetch('/api/doctor-status')
        .then(r=>r.json())
        .then(doctors=>{
            doctors.forEach(d=>{
                const badge=document.getElementById('badge-'+d.id);
                if(!badge)return;
                let dotClass='bg-green-500';
                if(d.current_status==='IN_EXAMINATION')dotClass='bg-yellow-500 animate-pulse';
                else if(d.current_status==='UNAVAILABLE')dotClass='bg-red-500';
                else if(d.current_status==='NEXT_AVAILABLE')dotClass='bg-blue-500';
                let label=d.status_label;
                if(d.current_status==='IN_EXAMINATION'&&d.current_queue_number)label+=' — Antrian No. '+d.current_queue_number;
                badge.className='inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full '+d.status_badge_class;
                badge.innerHTML='<span class="w-2 h-2 rounded-full '+dotClass+'"></span>'+label;
            });
        }).catch(()=>{});
},30000);
</script>
@endpush
@endsection
