@extends('layouts.admin')
@section('title', 'Kelola Spesialisasi — MyKlinik911')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Kelola Spesialisasi</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola daftar spesialisasi dokter. Spesialisasi default tidak dapat dihapus.</p>
    </div>
    <button onclick="openAddModal()"
        class="btn-primary inline-flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Spesialisasi
    </button>
</div>

{{-- Toast --}}
<div id="toast-container" class="fixed top-5 right-5 z-50 space-y-2"></div>

{{-- Table --}}
<div class="bg-white dark:bg-[#1c2622] border border-[#e2efe7] dark:border-[#283731] rounded-xl shadow-sm overflow-hidden transition-colors duration-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm" id="spec-table">
            <thead class="bg-[#F6FBF8] dark:bg-[#141b18] text-[#6B9080] dark:text-[#A8D5BA] text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-left w-12">#</th>
                    <th class="px-5 py-3 text-left">Label (Nama Tampilan)</th>
                    <th class="px-5 py-3 text-left">Kode</th>
                    <th class="px-5 py-3 text-center">Tipe</th>
                    <th class="px-5 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#e2efe7] dark:divide-[#283731]" id="spec-tbody">
                @foreach($specializations as $i => $spec)
                <tr id="spec-row-{{ $spec->id }}" class="hover:bg-[#F6FBF8] dark:hover:bg-[#1c2622]/50 transition-colors">
                    <td class="px-5 py-3 text-gray-400 dark:text-gray-500 text-xs">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900 dark:text-white" id="label-{{ $spec->id }}">
                        {{ $spec->label }}
                    </td>
                    <td class="px-5 py-3">
                        <code class="text-xs bg-[#F6FBF8] dark:bg-[#141b18] text-gray-600 dark:text-gray-300 px-2 py-0.5 rounded font-mono border border-[#e2efe7] dark:border-[#283731]">{{ $spec->value }}</code>
                    </td>
                    <td class="px-5 py-3 text-center">
                        @if($spec->is_default)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Default
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                Kustom
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center">
                        <div class="inline-flex items-center gap-2">
                            <button onclick="openEditModal({{ $spec->id }}, '{{ addslashes($spec->label) }}')"
                                class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full border border-brand/20 bg-brand/10 dark:bg-brand/20 text-brand dark:text-blue-300 hover:bg-brand/20 dark:hover:bg-brand/35 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                Edit
                            </button>
                            @if(!$spec->is_default)
                            <button onclick="deleteSpec({{ $spec->id }}, '{{ addslashes($spec->label) }}')"
                                class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full border border-red-200 dark:border-red-900/35 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-950/50 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                            @else
                            <span class="inline-flex items-center text-xs text-gray-300 dark:text-gray-600 px-3 py-1.5">—</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-3 border-t border-[#e2efe7] dark:border-[#283731] text-xs text-gray-400 dark:text-gray-500">
        Total: <span id="spec-count">{{ $specializations->count() }}</span> spesialisasi
    </div>
</div>

{{-- ── MODAL TAMBAH ── --}}
<div id="modal-add" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden" onclick="if(event.target===this)closeAddModal()">
    <div class="bg-white dark:bg-[#1c2622] border border-[#e2efe7] dark:border-[#283731] rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 transition-colors duration-200">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tambah Spesialisasi Baru</h2>
        <div id="add-error" class="hidden mb-3 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm"></div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Spesialisasi</label>
            <input type="text" id="add-label" placeholder="contoh: Spesialis Saraf"
                class="input-base w-full" onkeydown="if(event.key==='Enter')submitAdd()">
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kode akan dibuat otomatis dari nama.</p>
        </div>
        <div class="flex gap-3 justify-end">
            <button onclick="closeAddModal()" class="btn-outline">Batal</button>
            <button onclick="submitAdd()" id="add-btn" class="btn-primary">Simpan</button>
        </div>
    </div>
</div>

{{-- ── MODAL EDIT ── --}}
<div id="modal-edit" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden" onclick="if(event.target===this)closeEditModal()">
    <div class="bg-white dark:bg-[#1c2622] border border-[#e2efe7] dark:border-[#283731] rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6 transition-colors duration-200">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Edit Spesialisasi</h2>
        <div id="edit-error" class="hidden mb-3 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm"></div>
        <input type="hidden" id="edit-id">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Spesialisasi</label>
            <input type="text" id="edit-label" class="input-base w-full" onkeydown="if(event.key==='Enter')submitEdit()">
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Kode (value) tidak berubah, hanya nama tampilan yang diperbarui.</p>
        </div>
        <div class="flex gap-3 justify-end">
            <button onclick="closeEditModal()" class="btn-outline">Batal</button>
            <button onclick="submitEdit()" id="edit-btn" class="btn-primary">Perbarui</button>
        </div>
    </div>
</div>

<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;

// ── TOAST ──
function showToast(msg, type = 'success') {
    const c = document.getElementById('toast-container');
    const t = document.createElement('div');
    t.className = `px-4 py-3 rounded-xl shadow-lg text-sm font-medium transition-all ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    t.textContent = msg;
    c.appendChild(t);
    setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3000);
}

// ── TAMBAH ──
function openAddModal() {
    document.getElementById('add-label').value = '';
    document.getElementById('add-error').classList.add('hidden');
    document.getElementById('modal-add').classList.remove('hidden');
    setTimeout(() => document.getElementById('add-label').focus(), 100);
}
function closeAddModal() {
    document.getElementById('modal-add').classList.add('hidden');
}
async function submitAdd() {
    const label = document.getElementById('add-label').value.trim();
    const errEl = document.getElementById('add-error');
    if (!label) { errEl.textContent = 'Nama tidak boleh kosong.'; errEl.classList.remove('hidden'); return; }

    const btn = document.getElementById('add-btn');
    btn.disabled = true; btn.textContent = 'Menyimpan…';

    try {
        const res = await fetch('{{ route('admin.specializations.store') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ label }),
        });
        const data = await res.json();
        if (!res.ok || !data.success) {
            errEl.textContent = data.message || 'Gagal menyimpan.';
            errEl.classList.remove('hidden');
            return;
        }
        closeAddModal();
        showToast(data.message);
        // Reload page to show new row
        setTimeout(() => location.reload(), 800);
    } catch (e) {
        errEl.textContent = 'Terjadi kesalahan. Coba lagi.';
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false; btn.textContent = 'Simpan';
    }
}

// ── EDIT ──
function openEditModal(id, label) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-label').value = label;
    document.getElementById('edit-error').classList.add('hidden');
    document.getElementById('modal-edit').classList.remove('hidden');
    setTimeout(() => document.getElementById('edit-label').focus(), 100);
}
function closeEditModal() {
    document.getElementById('modal-edit').classList.add('hidden');
}
async function submitEdit() {
    const id    = document.getElementById('edit-id').value;
    const label = document.getElementById('edit-label').value.trim();
    const errEl = document.getElementById('edit-error');
    if (!label) { errEl.textContent = 'Nama tidak boleh kosong.'; errEl.classList.remove('hidden'); return; }

    const btn = document.getElementById('edit-btn');
    btn.disabled = true; btn.textContent = 'Menyimpan…';

    try {
        const res = await fetch(`/admin/specializations/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify({ label }),
        });
        const data = await res.json();
        if (!res.ok || !data.success) {
            errEl.textContent = data.message || 'Gagal memperbarui.';
            errEl.classList.remove('hidden');
            return;
        }
        // Update label in row without reload
        const labelEl = document.getElementById(`label-${id}`);
        if (labelEl) labelEl.textContent = label;
        closeEditModal();
        showToast(data.message);
    } catch (e) {
        errEl.textContent = 'Terjadi kesalahan. Coba lagi.';
        errEl.classList.remove('hidden');
    } finally {
        btn.disabled = false; btn.textContent = 'Perbarui';
    }
}

// ── HAPUS ──
async function deleteSpec(id, label) {
    if (!confirm(`Hapus spesialisasi "${label}"?\n\nPastikan tidak ada dokter yang menggunakan spesialisasi ini.`)) return;

    try {
        const res = await fetch(`/admin/specializations/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        });
        const data = await res.json();
        if (!res.ok || !data.success) {
            showToast(data.message || 'Gagal menghapus.', 'error');
            return;
        }
        // Remove row from table
        const row = document.getElementById(`spec-row-${id}`);
        if (row) {
            row.style.opacity = '0';
            row.style.transition = 'opacity 0.3s';
            setTimeout(() => {
                row.remove();
                // Update count
                const countEl = document.getElementById('spec-count');
                if (countEl) countEl.textContent = parseInt(countEl.textContent) - 1;
            }, 300);
        }
        showToast(data.message);
    } catch (e) {
        showToast('Terjadi kesalahan. Coba lagi.', 'error');
    }
}
</script>
@endsection
