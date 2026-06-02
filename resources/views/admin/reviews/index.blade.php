@extends('layouts.admin')
@section('title', 'Moderasi Review — MyKlinik911')
@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Moderasi Review</h1>
        <div class="flex gap-2 text-xs">
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2 rounded-lg font-bold transition-all {{ !request('status') ? 'bg-brand text-white shadow-lg' : 'bg-white dark:bg-[#1c2622] text-gray-500 border border-[#e2efe7] dark:border-[#283731]' }}">Semua</a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg font-bold transition-all {{ request('status') === 'pending' ? 'bg-yellow-500 text-white shadow-lg' : 'bg-white dark:bg-[#1c2622] text-gray-500 border border-[#e2efe7] dark:border-[#283731]' }}">Pending ({{ $stats['pending'] }})</a>
            <a href="{{ route('admin.reviews.index', ['status' => 'published']) }}" class="px-4 py-2 rounded-lg font-bold transition-all {{ request('status') === 'published' ? 'bg-green-500 text-white shadow-lg' : 'bg-white dark:bg-[#1c2622] text-gray-500 border border-[#e2efe7] dark:border-[#283731]' }}">Published</a>
        </div>
    </div>

    {{-- Admin Metrics --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-[#1c2622] p-6 rounded-2xl border border-[#e2efe7] dark:border-[#283731] flex items-center gap-4 group transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/10 text-blue-500 flex items-center justify-center font-black text-xl">{{ $stats['total'] }}</div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Review</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">Keseluruhan Data</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1c2622] p-6 rounded-2xl border border-[#e2efe7] dark:border-[#283731] flex items-center gap-4 group transition-all hover:shadow-md">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 dark:bg-yellow-900/10 text-yellow-600 flex items-center justify-center font-black text-xl">{{ $stats['pending'] }}</div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Perlu Moderasi</p>
                <p class="text-sm font-bold text-gray-900 dark:text-white">Review Baru Masuk</p>
            </div>
        </div>
        <div class="bg-white dark:bg-[#1c2622] p-6 rounded-2xl border border-[#e2efe7] dark:border-[#283731] flex items-center gap-4 group transition-all hover:shadow-md {{ $stats['flagged'] > 0 ? 'ring-2 ring-red-500 ring-inset' : '' }}">
            <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/10 text-red-600 flex items-center justify-center font-black text-xl">{{ $stats['flagged'] }}</div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Terdeteksi Kasar</p>
                <p class="text-sm font-bold {{ $stats['flagged'] > 0 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">Segera Periksa</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-[#1c2622] rounded-xl shadow-sm border border-[#e2efe7] dark:border-[#283731] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#F6FBF8] dark:bg-[#141b18] text-[#6B9080] dark:text-[#A8D5BA] text-xs uppercase">
                    <tr>
                        <th class="px-6 py-4 text-left">Pasien</th>
                        <th class="px-6 py-4 text-left">Review Untuk</th>
                        <th class="px-6 py-4 text-left">Rating</th>
                        <th class="px-6 py-4 text-left w-1/3">Komentar</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#e2efe7] dark:divide-[#283731]">
                    @forelse($reviews as $r)
                        <tr class="hover:bg-[#F6FBF8]/50 dark:hover:bg-[#141b18]/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-white">{{ $r->user->name }}</div>
                                <div class="text-[10px] text-gray-400">Booking: {{ $r->booking->booking_code ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($r->type === 'doctor')
                                    <span class="text-xs font-bold text-brand uppercase tracking-tighter">👨‍⚕️ Dokter</span>
                                    <div class="text-xs">{{ $r->doctor->name ?? 'N/A' }}</div>
                                @else
                                    <span class="text-xs font-bold text-teal-600 uppercase tracking-tighter">🏥 Klinik</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-amber-400 text-lg">{{ $r->rating_stars }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative group">
                                    <p class="text-gray-600 dark:text-gray-300 line-clamp-2 italic" :class="{'line-clamp-none': open}">
                                        "{{ $r->comment }}"
                                    </p>
                                    @if($r->is_flagged)
                                        <div class="mt-2 flex items-center gap-1.5 text-[10px] font-bold text-red-500 uppercase">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.34c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                                            Terdeteksi Kata Kasar
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest
                                    @if($r->isPending()) bg-yellow-100 text-yellow-700 @elseif($r->isPublished()) bg-green-100 text-green-700 @else bg-gray-100 text-gray-700 @endif">
                                    {{ $r->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2" x-data="{ openOptions: false }">
                                    {{-- Approve Button --}}
                                    @if(!$r->isPublished())
                                        <form action="{{ route('admin.reviews.update', $r) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="published">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition-colors shadow-sm" title="Terbitkan/Approve">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Hide Button --}}
                                    @if(!$r->isHidden())
                                        <form action="{{ route('admin.reviews.update', $r) }}" method="POST">
                                            @csrf @method('PUT')
                                            <input type="hidden" name="status" value="hidden">
                                            <button type="submit" class="w-8 h-8 rounded-lg bg-gray-500 text-white flex items-center justify-center hover:bg-gray-600 transition-colors shadow-sm" title="Sembunyikan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Delete Button --}}
                                    <form action="{{ route('admin.reviews.destroy', $r) }}" method="POST" onsubmit="return confirm('Hapus permanen review ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm" title="Hapus Permanen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">Tidak ada review yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-[#e2efe7] dark:border-[#283731]">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
@endsection
