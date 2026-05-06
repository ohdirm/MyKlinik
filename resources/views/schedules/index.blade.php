@extends('layouts.app')

@section('title', 'Jadwal - Klinik App')
@section('header_title', 'Manajemen Jadwal')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">Daftar Jadwal Dokter</h3>
        <p class="text-sm text-gray-500 mt-1">Kelola hari dan jam praktik dokter.</p>
    </div>
    <a href="{{ route('schedules.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
        + Tambah Jadwal
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">Dokter</th>
                    <th class="px-6 py-4">Klinik</th>
                    <th class="px-6 py-4">Hari</th>
                    <th class="px-6 py-4">Waktu</th>
                    <th class="px-6 py-4">Kuota Maksimal</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($schedules as $schedule)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $schedule->doctor->name }}</td>
                    <td class="px-6 py-4">{{ $schedule->doctor->clinic->name }}</td>
                    <td class="px-6 py-4 capitalize">{{ $schedule->day_of_week }}</td>
                    <td class="px-6 py-4">{{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}</td>
                    <td class="px-6 py-4">{{ $schedule->max_patients }} Pasien</td>
                    <td class="px-6 py-4">
                        @if($schedule->is_active)
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Aktif</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('schedules.edit', $schedule) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                            <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data jadwal.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/30">
        {{ $schedules->links() }}
    </div>
</div>
@endsection
