@extends('layouts.admin')
@section('title', 'Edit Dokter — MyKlinik911')
@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Dokter</h1>
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('admin.doctors.update', $doctor) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" class="input-base" value="{{ old('name', $doctor->name) }}" required>
            </div>

            {{-- ── SPESIALISASI dengan tombol tambah baru ── --}}
            <div x-data="specializationPicker('{{ old('specialization', $doctor->specialization) }}')" x-init="init()">
                <label class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                <div class="flex gap-2 items-center">
                    <select name="specialization" x-ref="selectEl" class="input-base flex-1" required>
                        @foreach($specializations as $spec)
                            <option value="{{ $spec->value }}"
                                {{ old('specialization', $doctor->specialization) == $spec->value ? 'selected' : '' }}>
                                {{ $spec->label }}{{ !$spec->is_default ? ' ✦' : '' }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" @click="openModal()"
                        title="Tambah spesialisasi baru"
                        class="flex-shrink-0 inline-flex items-center gap-1 px-3 py-2 bg-brand text-white text-sm font-medium rounded-lg hover:bg-brand/90 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-400">✦ = spesialisasi kustom</p>

                {{-- Modal Tambah Spesialisasi --}}
                <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40" style="display:none">
                    <div @click.outside="closeModal()" class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-6" x-transition.scale>
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Tambah Spesialisasi Baru</h2>

                        <div x-show="errorMsg" class="mb-3 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-lg text-sm" x-text="errorMsg"></div>
                        <div x-show="successMsg" class="mb-3 bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded-lg text-sm" x-text="successMsg"></div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Spesialisasi</label>
                            <input type="text" x-model="newLabel"
                                @keydown.enter.prevent="saveSpecialization()"
                                placeholder="contoh: Spesialis Saraf"
                                class="input-base w-full">
                            <p class="text-xs text-gray-400 mt-1">Kode otomatis dibuat dari nama yang Anda masukkan.</p>
                        </div>

                        <div class="flex gap-3 justify-end">
                            <button type="button" @click="closeModal()" class="btn-outline">Batal</button>
                            <button type="button" @click="saveSpecialization()" :disabled="saving"
                                class="btn-primary disabled:opacity-60">
                                <span x-show="!saving">Simpan</span>
                                <span x-show="saving">Menyimpan…</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ── END SPESIALISASI ── --}}

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <textarea name="bio" class="input-base" rows="3">{{ old('bio', $doctor->bio) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                @if($doctor->photo)<p class="text-xs text-gray-500 mb-1">Foto saat ini: {{ basename($doctor->photo) }}</p>@endif
                <input type="file" name="photo" accept="image/*" class="input-base">
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-brand focus:ring-brand" {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}>
                <label class="text-sm text-gray-700">Aktif</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Perbarui</button>
                <a href="{{ route('admin.doctors.index') }}" class="btn-outline">Batal</a>
            </div>
        </div>
    </form>
</div>

<script>
function specializationPicker(currentValue) {
    return {
        showModal: false,
        newLabel: '',
        saving: false,
        errorMsg: '',
        successMsg: '',

        init() {
            if (currentValue && this.$refs.selectEl) {
                this.$refs.selectEl.value = currentValue;
            }
        },

        openModal() {
            this.newLabel = '';
            this.errorMsg = '';
            this.successMsg = '';
            this.showModal = true;
            this.$nextTick(() => {
                const inp = this.$el.querySelector('input[type=text]');
                if (inp) inp.focus();
            });
        },

        closeModal() {
            this.showModal = false;
        },

        async saveSpecialization() {
            this.errorMsg = '';
            this.successMsg = '';
            const label = this.newLabel.trim();
            if (!label) { this.errorMsg = 'Nama spesialisasi tidak boleh kosong.'; return; }

            this.saving = true;
            try {
                const res = await fetch('{{ route('admin.specializations.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ label }),
                });

                const data = await res.json();

                if (!res.ok || !data.success) {
                    this.errorMsg = data.message || 'Gagal menyimpan spesialisasi.';
                    return;
                }

                // Tambahkan opsi baru ke select dan langsung pilih
                const opt = new Option(data.spec.label + ' ✦', data.spec.value, true, true);
                this.$refs.selectEl.add(opt);
                this.$refs.selectEl.value = data.spec.value;

                this.successMsg = data.message;
                setTimeout(() => this.closeModal(), 1200);
            } catch (e) {
                this.errorMsg = 'Terjadi kesalahan. Coba lagi.';
            } finally {
                this.saving = false;
            }
        },
    };
}
</script>
@endsection
