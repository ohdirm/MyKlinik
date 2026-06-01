@extends('layouts.admin')

@section('title', 'Manajemen Staff — MyKlinik911')
@section('page_title', 'Manajemen Staff')
@section('page_subtitle', 'Kelola hak akses dan akun pekerja klinik')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">
    {{-- Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-extrabold text-gray-800 dark:text-white tracking-tight">Daftar Akun Staff</h3>
            <p class="text-xs text-gray-500 font-medium uppercase tracking-widest">Total: {{ $staffs->count() }} Akun</p>
        </div>
        <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-brand hover:bg-brand-dark text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-brand/20 active:scale-95">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Staff Baru
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 px-6 py-4 rounded-3xl text-sm flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
        </div>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-6 py-4 rounded-3xl text-sm flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <span class="font-bold">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-white dark:bg-[#1c2622] rounded-[2.5rem] shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-gray-800/20 text-gray-400 text-[10px] uppercase font-black tracking-widest border-b border-gray-100 dark:border-gray-800">
                        <th class="px-8 py-6 text-left">Nama & Email</th>
                        <th class="px-8 py-6 text-left">Role</th>
                        <th class="px-8 py-6 text-left">Terdaftar Pada</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                    @foreach($staffs as $s)
                    <tr class="group hover:bg-brand-light/10 dark:hover:bg-brand/5 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-2xl bg-brand/10 text-brand flex items-center justify-center font-black text-xs">
                                    {{ strtoupper(substr($s->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-extrabold text-gray-800 dark:text-white">{{ $s->name }}</p>
                                    <p class="text-xs text-brand/70 font-medium">{{ $s->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                                {{ $s->role }}
                            </span>
                        </td>
                        <td class="px-8 py-5 text-gray-500 dark:text-gray-400 font-medium">
                            {{ $s->created_at->format('d M Y') }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.staff.edit', $s->id) }}" class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 hover:bg-brand hover:text-white transition-all shadow-sm" title="Edit Staff">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </a>
                                @if($s->id !== auth()->id())
                                <form action="{{ route('admin.staff.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun staff ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2.5 rounded-xl bg-gray-100 dark:bg-gray-800 text-gray-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm cursor-pointer" title="Hapus Staff">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
