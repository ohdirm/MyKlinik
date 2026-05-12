@extends('layouts.admin')
@section('title', 'Kelola Jadwal — MyKlinik911')
@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Jadwal Dokter</h1>
    <a href="{{ route('admin.schedules.create') }}" class="btn-primary">+ Tambah Jadwal</a>
</div>
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">Dokter</th>
                    <th class="px-4 py-3 text-left">Hari</th>
                    <th class="px-4 py-3 text-left">Jam</th>
                    <th class="px-4 py-3 text-left">Maks Pasien</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $s->doctor->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $s->day_name }}</td>
                    <td class="px-4 py-3">{{ $s->time_range }}</td>
                    <td class="px-4 py-3">{{ $s->max_patients }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.schedules.edit', $s) }}" class="text-xs text-brand hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.schedules.destroy', $s) }}" onsubmit="return confirm('Hapus jadwal ini?')">@csrf @method('DELETE')<button class="text-xs text-red-500 hover:underline cursor-pointer">Hapus</button></form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">{{ $schedules->links() }}</div>
</div>
@endsection
