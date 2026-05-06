@extends('layouts.app')

@section('title', 'Klinik - Klinik App')
@section('header_title', 'Manajemen Klinik')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">Daftar Klinik</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola data klinik yang tersedia.</p>
    </div>
    <a href="{{ route('clinics.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
        + Tambah Klinik
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Nama Klinik</th>
                    <th class="px-6 py-4">Alamat</th>
                    <th class="px-6 py-4">Telepon</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($clinics as $clinic)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $clinic->name }}</td>
                    <td class="px-6 py-4">{{ $clinic->address }}</td>
                    <td class="px-6 py-4">{{ $clinic->phone ?? '-' }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('clinics.edit', $clinic) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                            <form action="{{ route('clinics.destroy', $clinic) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus klinik ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data klinik.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/30">
        {{ $clinics->links() }}
    </div>
</div>
@endsection
