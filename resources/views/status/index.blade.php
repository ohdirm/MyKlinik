@extends('layouts.app')
@section('title', 'Status Dokter — MyKlinik911')
@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Back Button --}}
        <div class="mb-6 flex">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 group text-sm font-medium transition-all" style="color: var(--ui-text-muted);">
                <div class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-800 flex items-center justify-center transition-all group-hover:border-brand group-hover:bg-brand group-hover:text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                </div>
                <span class="group-hover:text-brand">Kembali ke Beranda</span>
            </a>
        </div>

        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Status Dokter</h1>
            <p class="text-gray-500 dark:text-gray-400">Pantau ketersediaan dokter secara real-time</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="doctor-status-grid">
            @foreach($doctors as $doctor)
            @php $st = $doctor->status; @endphp
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 p-6 hover:shadow-md transition-all duration-200" id="doctor-card-{{ $doctor->id }}">
                <div class="flex items-center gap-4 mb-4">
                    @if($doctor->photo)
                        <img src="{{ asset('storage/' . $doctor->photo) }}"
                             alt="{{ $doctor->name }}"
                             class="w-14 h-14 rounded-full object-cover shrink-0 border-2 border-brand/20">
                    @else
                        <div class="w-14 h-14 rounded-full bg-brand-light dark:bg-brand/20 text-brand dark:text-blue-300 flex items-center justify-center font-bold text-lg shrink-0">{{ $doctor->initials }}</div>
                    @endif
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $doctor->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $doctor->specialization_label }}</p>
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
