@extends('layouts.admin')
@section('title', 'Kelola Dokter — MyKlinik911')
@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dokter</h1>
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('admin.doctors.index') }}" id="filter-doctor-spec-form">
            <select name="specialization" onchange="document.getElementById('filter-doctor-spec-form').submit()"
                    class="text-sm rounded-lg border border-[#e2efe7] dark:border-[#283731] bg-white dark:bg-[#1c2622] text-gray-700 dark:text-gray-200 px-3 py-2 pr-8 focus:ring-2 focus:ring-brand/30 focus:border-brand transition cursor-pointer min-w-[200px]">
                <option value="">Semua Spesialisasi</option>
                @foreach($specializations as $spec)
                    <option value="{{ $spec->value }}" {{ request('specialization') === $spec->value ? 'selected' : '' }}>{{ $spec->label }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.doctors.create') }}" class="btn-primary">+ Tambah Dokter</a>
    </div>
</div>
<div class="bg-white dark:bg-[#1c2622] border border-[#e2efe7] dark:border-[#283731] rounded-xl shadow-sm overflow-hidden transition-colors duration-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-[#F6FBF8] dark:bg-[#141b18] text-[#6B9080] dark:text-[#A8D5BA] text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Spesialisasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e2efe7] dark:divide-[#283731]">
                @forelse($doctors as $d)
                <tr class="hover:bg-[#F6FBF8] dark:hover:bg-[#1c2622]/50">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand to-teal-500 flex items-center justify-center text-white font-bold text-sm shrink-0 shadow-sm border border-white dark:border-[#283731] overflow-hidden">
                                @if($d->photo_url)
                                    <img src="{{ $d->photo_url }}" alt="{{ $d->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ $d->initials }}
                                @endif
                            </div>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $d->name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400 font-medium">{{ $d->specialization_label }}</td>
                    <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $d->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $d->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="px-4 py-3 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.doctors.edit', $d) }}" class="inline-flex items-center justify-center rounded-full border border-brand/20 bg-brand/10 dark:bg-brand/20 px-3 py-1 text-[11px] font-semibold text-brand dark:text-[#A8D5BA] transition hover:bg-brand/20 dark:hover:bg-brand/35">Edit</a>
                            <form method="POST" action="{{ route('admin.doctors.destroy', $d) }}" onsubmit="return confirm('Hapus dokter ini?')" class="inline-flex items-center">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-full border border-red-200 dark:border-red-900/35 bg-red-50 dark:bg-red-950/30 px-3 py-1 text-[11px] font-semibold text-red-600 dark:text-red-300 transition hover:bg-red-100 dark:hover:bg-red-950/50">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-[#e2efe7] dark:border-[#283731]">{{ $doctors->links() }}</div>
</div>
@endsection
