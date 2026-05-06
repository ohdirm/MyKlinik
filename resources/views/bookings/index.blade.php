@extends('layouts.app')

@section('title', 'Booking - Klinik App')
@section('header_title', 'Manajemen Booking')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h3 class="text-2xl font-bold text-gray-800">Daftar Booking</h3>
        <p class="text-sm text-gray-500 mt-1">
            {{ auth()->user()->isAdmin() ? 'Kelola semua data booking pasien.' : 'Daftar riwayat booking Anda.' }}
        </p>
    </div>
    <a href="{{ route('bookings.create') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
        + Buat Booking Baru
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50/50 text-gray-500 font-medium border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4">ID / No. Antrian</th>
                    @if(auth()->user()->isAdmin())
                    <th class="px-6 py-4">Pasien</th>
                    @endif
                    <th class="px-6 py-4">Dokter & Klinik</th>
                    <th class="px-6 py-4">Tanggal Booking</th>
                    <th class="px-6 py-4">Waktu Praktik</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">#BK-{{ str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</div>
                        <div class="mt-1">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200" title="Nomor Antrian">
                                {{ $booking->queue_number }}
                            </span>
                        </div>
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-6 py-4">{{ $booking->user->name }}</td>
                    @endif
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $booking->schedule->doctor->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $booking->schedule->doctor->clinic->name }} ({{ $booking->schedule->doctor->specialization->name }})</div>
                    </td>
                    <td class="px-6 py-4">{{ $booking->booking_date->format('d M Y') }}</td>
                    <td class="px-6 py-4">{{ $booking->schedule->start_time->format('H:i') }} - {{ $booking->schedule->end_time->format('H:i') }}</td>
                    <td class="px-6 py-4">
                        @if($booking->status == 'pending')
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 border border-yellow-200">Pending</span>
                        @elseif($booking->status == 'confirmed')
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">Confirmed</span>
                        @elseif($booking->status == 'in_progress')
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 border border-blue-200">In Progress</span>
                        @elseif($booking->status == 'completed')
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">Completed</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">Cancelled</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('bookings.edit', $booking) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Detail/Edit</a>
                            @if(auth()->user()->isAdmin())
                            <form action="{{ route('bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus booking ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isAdmin() ? '7' : '6' }}" class="px-6 py-4 text-center text-gray-500">Belum ada data booking.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/30">
        {{ $bookings->links() }}
    </div>
</div>
@endsection
