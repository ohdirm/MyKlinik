@extends('layouts.admin')

@section('title', 'Log Aktivitas — MyKlinik911')
@section('page_title', 'Audit Trail')
@section('page_subtitle', 'Rekaman aktivitas staff secara real-time')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-extrabold text-gray-800 dark:text-white tracking-tight">Riwayat Aktivitas Sistem</h3>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Memantau setiap aksi krusial staff</p>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1c2622] rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-800/20 text-gray-400 text-[10px] uppercase font-black tracking-widest border-b border-gray-100 dark:border-gray-800">
                        <th class="px-8 py-6 text-left">Waktu</th>
                        <th class="px-8 py-6 text-left">Pelaku</th>
                        <th class="px-8 py-6 text-left">Aksi</th>
                        <th class="px-8 py-6 text-left">Keterangan</th>
                        <th class="px-8 py-6 text-left whitespace-nowrap">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                    @forelse($logs as $log)
                    <tr class="group hover:bg-gray-50/50 dark:hover:bg-brand/5 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-900 dark:text-gray-100">{{ $log->created_at->format('d M Y') }}</span>
                                <span class="text-[10px] text-gray-400 font-medium">{{ $log->created_at->format('H:i:s') }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl {{ $log->user->isSuperAdmin() ? 'bg-brand/10 text-brand' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600' }} flex items-center justify-center font-black text-[10px]">
                                    {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-extrabold text-gray-800 dark:text-white leading-none mb-0.5">{{ $log->user->name ?? 'Unknown' }}</span>
                                    <span class="text-[9px] uppercase tracking-tighter {{ $log->user->isSuperAdmin() ? 'text-brand' : 'text-blue-500' }} font-bold">{{ $log->user->isSuperAdmin() ? 'Super Admin' : 'Staff' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-8 py-5">
                            <p class="text-gray-500 dark:text-gray-400 leading-relaxed max-w-md">{{ $log->description }}</p>
                        </td>
                        <td class="px-8 py-5">
                            <code class="text-[10px] bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded-lg text-gray-400">{{ $log->ip_address }}</code>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-12 text-center text-gray-400 italic">Belum ada aktivitas yang tercatat ✨</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-8 py-6 bg-gray-50/50 dark:bg-gray-800/10 border-t border-gray-100 dark:border-gray-800">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
