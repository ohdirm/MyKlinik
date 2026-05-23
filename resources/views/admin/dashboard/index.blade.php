@extends('layouts.admin')
@section('title', 'Dashboard — MyKlinik911')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard</h1>
</div>

@push('scripts')
<script>
function resetDaily() {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    fetch('{{ route("admin.reset-daily") }}', {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': csrf, 'Accept': 'application/json'}
    }).then(r => r.json()).then(data => {
        if (data.success) {
            document.getElementById('reset-modal').classList.add('hidden');
            alert(data.message);
            window.location.reload();
        }
    }).catch(() => alert('Gagal mereset.'));
}
</script>
@endpush
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border-l-4 border-yellow-400 border border-gray-100 dark:border-gray-800 transition-colors duration-200">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Pending</p>
        <p class="text-3xl font-bold text-yellow-600">{{ $metrics['pending'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border-l-4 border-green-400 border border-gray-100 dark:border-gray-800 transition-colors duration-200">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Confirmed</p>
        <p class="text-3xl font-bold text-green-600">{{ $metrics['confirmed'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border-l-4 border-red-400 border border-gray-100 dark:border-gray-800 transition-colors duration-200">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Rejected</p>
        <p class="text-3xl font-bold text-red-600">{{ $metrics['rejected'] }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm p-6 border-l-4 border-gray-400 border border-gray-100 dark:border-gray-800 transition-colors duration-200">
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Done</p>
        <p class="text-3xl font-bold text-gray-600">{{ $metrics['done'] }}</p>
    </div>
</div>
<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-800 transition-colors duration-200">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
        <h2 class="font-semibold text-gray-900 dark:text-white">Booking Terbaru</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-950 text-gray-600 dark:text-gray-400 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Dokter</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Antrian</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($recentBookings as $b)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="px-4 py-3 font-mono font-semibold text-brand dark:text-blue-400">{{ $b->booking_code }}</td>
                    <td class="px-4 py-3">{{ $b->patient_name }}</td>
                    <td class="px-4 py-3">{{ $b->doctor->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $b->exam_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 font-semibold">{{ $b->queue_number }}</td>
                    <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $b->status_badge_class }}">{{ $b->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada booking</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
