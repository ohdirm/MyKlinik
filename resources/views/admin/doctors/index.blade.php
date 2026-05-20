@extends('layouts.admin')
@section('title', 'Kelola Dokter — MyKlinik911')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dokter</h1>
    <a href="{{ route('admin.doctors.create') }}" class="btn-primary">+ Tambah Dokter</a>
</div>
<div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl shadow-sm overflow-hidden transition-colors duration-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-950 text-gray-600 dark:text-gray-400 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Spesialisasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($doctors as $d)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $d->name }}</td>
                    <td class="px-4 py-3">{{ $d->specialization_label }}</td>
                    <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $d->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $d->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="px-4 py-3 text-center">
                        <div class="inline-flex items-center gap-2">
                            <a href="{{ route('admin.doctors.edit', $d) }}" class="inline-flex items-center justify-center rounded-full border border-brand/20 bg-brand/10 dark:bg-brand/20 px-3 py-1 text-[11px] font-semibold text-brand dark:text-blue-300 transition hover:bg-brand/20 dark:hover:bg-brand/35">Edit</a>
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
    <div class="px-4 py-3 border-t border-gray-100 dark:border-gray-800">{{ $doctors->links() }}</div>
</div>
@endsection
