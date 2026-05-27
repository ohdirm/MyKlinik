{{-- Family Profiles Partial --}}
<div class="space-y-6">
    {{-- Add Button --}}
    <button @click="openAddFamily()" class="w-full flex items-center justify-center gap-2 py-4 rounded-2xl border-2 border-dashed border-brand/40 dark:border-brand/30 text-brand-dark dark:text-brand hover:bg-brand/5 dark:hover:bg-brand/10 hover:border-brand transition-all cursor-pointer group">
        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        <span class="font-semibold text-sm">Tambah Anggota Keluarga</span>
    </button>

    {{-- Family Cards --}}
    @forelse($familyProfiles as $member)
    <div class="bg-white dark:bg-[#1c2622] rounded-3xl border border-[#e2efe7] dark:border-[#283731] shadow-sm overflow-hidden hover:shadow-md transition-all duration-300 group">
        <div class="flex items-center gap-4 p-5">
            {{-- Avatar --}}
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 text-2xl
                {{ $member->gender === 'L' ? 'bg-blue-50 dark:bg-blue-900/20' : 'bg-pink-50 dark:bg-pink-900/20' }}">
                {{ $member->gender === 'L' ? '👨' : '👩' }}
            </div>

            {{-- Info --}}
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-900 dark:text-white text-sm truncate">{{ $member->full_name }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand/10 text-brand-dark dark:bg-brand/20 dark:text-brand">
                        {{ $member->relationship }}
                    </span>
                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ $member->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button @click="openEditFamily({{ $member->toJson() }})"
                        class="p-2 rounded-xl hover:bg-brand/10 dark:hover:bg-brand/20 text-gray-400 hover:text-brand transition-all cursor-pointer" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </button>
                <button @click="confirmDelete({{ $member->id }})"
                        class="p-2 rounded-xl hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-500 transition-all cursor-pointer" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
            </div>
        </div>

        {{-- Details (collapsed by default on mobile) --}}
        <div class="border-t border-[#e2efe7] dark:border-[#283731] px-5 py-3 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <div>
                <span class="text-gray-400 dark:text-gray-500 block mb-0.5">NIK</span>
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $member->nik }}</span>
            </div>
            <div>
                <span class="text-gray-400 dark:text-gray-500 block mb-0.5">Tanggal Lahir</span>
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $member->birth_date->format('d M Y') }}</span>
            </div>
            <div>
                <span class="text-gray-400 dark:text-gray-500 block mb-0.5">No HP</span>
                <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $member->phone_number }}</span>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-[#1c2622] rounded-3xl border border-[#e2efe7] dark:border-[#283731] p-10 text-center">
        <div class="w-16 h-16 bg-brand/10 dark:bg-brand/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-brand/40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
        </div>
        <p class="text-gray-400 dark:text-gray-500 text-sm">Belum ada anggota keluarga</p>
        <p class="text-gray-300 dark:text-gray-600 text-xs mt-1">Tambahkan anggota keluarga untuk booking atas nama mereka</p>
    </div>
    @endforelse
</div>

{{-- ══ Add / Edit Family Modal ══ --}}
<div x-show="showFamilyModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display: none;" @keydown.escape.window="showFamilyModal = false">
    <div x-show="showFamilyModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         @click.away="showFamilyModal = false"
         class="bg-white dark:bg-[#1c2622] rounded-3xl w-full max-w-lg shadow-2xl border border-[#e2efe7] dark:border-[#283731] overflow-hidden max-h-[90vh] overflow-y-auto">

        {{-- Modal Header --}}
        <div class="bg-gradient-to-r from-brand to-teal-600 px-6 py-4 flex items-center justify-between">
            <div>
                <h3 class="text-white font-bold text-lg" x-text="editingFamily ? 'Edit Anggota Keluarga' : 'Tambah Anggota Keluarga'"></h3>
                <p class="text-white/70 text-xs mt-0.5">Lengkapi data anggota keluarga</p>
            </div>
            <button @click="showFamilyModal = false" class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white hover:bg-white/30 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Modal Form --}}
        <form :action="editingFamily ? '{{ url('profile/family') }}/' + editingFamily.id : '{{ route('profile.family.store') }}'" method="POST" class="p-6 space-y-5">
            @csrf
            <template x-if="editingFamily"><input type="hidden" name="_method" value="PUT"></template>

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="full_name" x-model="familyForm.full_name" class="input-base" placeholder="Nama lengkap sesuai KTP" required>
            </div>

            {{-- Hubungan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Hubungan Keluarga <span class="text-red-500">*</span></label>
                <select name="relationship" x-model="familyForm.relationship" class="input-base" required>
                    <option value="">— Pilih Hubungan —</option>
                    <option value="Ayah">Ayah</option>
                    <option value="Ibu">Ibu</option>
                    <option value="Anak">Anak</option>
                    <option value="Saudara">Saudara</option>
                    <option value="Suami/Istri">Suami/Istri</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            {{-- NIK --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">NIK <span class="text-red-500">*</span></label>
                <input type="text" name="nik" x-model="familyForm.nik" class="input-base" maxlength="16" placeholder="Masukkan 16 digit NIK" required>
            </div>

            {{-- Tanggal Lahir --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" name="birth_date" x-model="familyForm.birth_date" class="input-base" required>
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white dark:bg-[#141b18]"
                           :class="familyForm.gender === 'L' ? 'border-brand bg-brand/5 dark:bg-brand/10' : 'border-[#e2efe7] dark:border-[#283731]'">
                        <input type="radio" name="gender" value="L" class="sr-only" x-model="familyForm.gender">
                        <span class="text-xl">👨</span><span class="font-medium text-sm text-gray-700 dark:text-gray-300">Laki-laki</span>
                    </label>
                    <label class="flex items-center gap-3 p-4 rounded-2xl border-2 cursor-pointer transition-all bg-white dark:bg-[#141b18]"
                           :class="familyForm.gender === 'P' ? 'border-brand bg-brand/5 dark:bg-brand/10' : 'border-[#e2efe7] dark:border-[#283731]'">
                        <input type="radio" name="gender" value="P" class="sr-only" x-model="familyForm.gender">
                        <span class="text-xl">👩</span><span class="font-medium text-sm text-gray-700 dark:text-gray-300">Perempuan</span>
                    </label>
                </div>
            </div>

            {{-- No HP --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">No HP/WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="phone_number" x-model="familyForm.phone_number" class="input-base" maxlength="15" placeholder="08xxxxxxxxxx" required>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="button" @click="showFamilyModal = false" class="flex-1 btn-outline py-3 text-sm">Batal</button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-brand to-[#85cca0] hover:from-[#96d7af] hover:to-brand text-white font-bold py-3 rounded-xl transition text-sm shadow-sm active:scale-95 cursor-pointer">
                    <span x-text="editingFamily ? 'Simpan Perubahan' : 'Tambah Anggota'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══ Delete Confirmation Modal ══ --}}
<div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" style="display: none;">
    <div x-show="showDeleteConfirm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         @click.away="showDeleteConfirm = false"
         class="bg-white dark:bg-[#1c2622] rounded-3xl w-full max-w-sm p-8 shadow-2xl border border-[#e2efe7] dark:border-[#283731] text-center">
        <div class="w-16 h-16 bg-red-50 dark:bg-red-900/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Hapus Anggota Keluarga?</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Data yang sudah dihapus tidak dapat dikembalikan.</p>
        <div class="flex gap-3">
            <button @click="showDeleteConfirm = false" class="flex-1 btn-outline py-3 text-sm">Batal</button>
            <form :action="'{{ url('profile/family') }}/' + deletingId" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl transition text-sm active:scale-95 cursor-pointer">Hapus</button>
            </form>
        </div>
    </div>
</div>
