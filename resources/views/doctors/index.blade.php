@extends('layouts.app')

@section('title', 'Dokter - Klinik App')
@section('header_title', 'Manajemen Dokter')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">Daftar Dokter</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola data dokter yang terdaftar di klinik.</p>
    </div>
    <a href="{{ route('doctors.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
        + Tambah Dokter
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Nama Dokter</th>
                    <th class="px-6 py-4">Klinik</th>
                    <th class="px-6 py-4">Spesialisasi</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($doctors as $doctor)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $doctor->name }}</td>
                    <td class="px-6 py-4">{{ $doctor->clinic->name }}</td>
                    <td class="px-6 py-4">{{ $doctor->specialization->name }}</td>
                    <td class="px-6 py-4">
                        @if($doctor->is_active)
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('doctors.edit', $doctor) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                            <form action="{{ route('doctors.destroy', $doctor) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data dokter.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/30">
        {{ $doctors->links() }}
    </div>
</div>
@endsection
