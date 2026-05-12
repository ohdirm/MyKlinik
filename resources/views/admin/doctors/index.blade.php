@extends('layouts.admin')
@section('title', 'Kelola Dokter — MyKlinik911')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Dokter</h1>
    <a href="{{ route('admin.doctors.create') }}" class="btn-primary">+ Tambah Dokter</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Spesialisasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($doctors as $d)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $d->name }}</td>
                    <td class="px-4 py-3">{{ $d->specialization_label }}</td>
                    <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full {{ $d->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $d->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.doctors.edit', $d) }}" class="text-xs text-brand hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.doctors.destroy', $d) }}" onsubmit="return confirm('Hapus dokter ini?')">@csrf @method('DELETE')<button class="text-xs text-red-500 hover:underline cursor-pointer">Hapus</button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $doctors->links() }}</div>
</div>
@endsection
