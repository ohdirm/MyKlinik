@extends('layouts.admin')

@section('title', 'Kelola Slide Homepage — Admin MyKlinik911')

@section('content')
<div class="space-y-6" x-data="{ 
    modalOpen: false, 
    editMode: false,
    slideId: '',
    slideTitle: '',
    slideDesc: '',
    slideOrder: 1,
    slideImagePreview: '',
    formAction: '',

    openAddModal() {
        this.editMode = false;
        this.slideId = '';
        this.slideTitle = '';
        this.slideDesc = '';
        this.slideOrder = 1;
        this.slideImagePreview = '';
        this.formAction = '{{ route('admin.home-slides.store') }}';
        this.modalOpen = true;
    },

    openEditModal(slide) {
        this.editMode = true;
        this.slideId = slide.id;
        this.slideTitle = slide.title.replace(/<br>/g, '\n');
        this.slideDesc = slide.desc;
        this.slideOrder = slide.order;
        this.slideImagePreview = slide.image.startsWith('http') || slide.image.startsWith('storage') || slide.image.startsWith('assets') ? '/' + slide.image : slide.image;
        this.formAction = '{{ url('admin/home-slides') }}/' + slide.id;
        this.modalOpen = true;
    }
}">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Slide Homepage</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Atur gambar latar belakang, judul, dan penjelasan pada carousel halaman depan.</p>
        </div>
        <button @click="openAddModal()" class="inline-flex items-center gap-1.5 px-4.5 py-2.5 bg-brand hover:bg-brand-dark text-white font-semibold text-sm rounded-xl transition duration-200 cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Slide Baru
        </button>
    </div>

    {{-- Alert --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-900/35 text-emerald-800 dark:text-emerald-300 px-4 py-3.5 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- Slides Grid/Table --}}
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-850 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-4 text-center w-20">Urutan</th>
                        <th class="px-6 py-4 w-48">Pratinjau Foto</th>
                        <th class="px-6 py-4">Informasi Slide</th>
                        <th class="px-6 py-4 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                    @foreach($slides as $slide)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-850/35 transition-colors">
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-brand-light dark:bg-brand/20 text-brand dark:text-blue-300 font-bold">
                                {{ $slide->order }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="relative w-40 h-24 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-800">
                                <img src="{{ asset($slide->image) }}" class="w-full h-full object-cover" alt="Slide preview">
                                <div class="absolute inset-0 bg-[#0F5D8C]/40"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-md">
                            <div class="space-y-1.5">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base leading-snug">{!! $slide->title !!}</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-xs line-clamp-2">{{ $slide->desc }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-2">
                                <button @click='openEditModal({!! json_encode($slide) !!})'
                                    class="inline-flex items-center gap-1 text-xs font-semibold px-3.5 py-2 rounded-full border border-brand/20 bg-brand/10 dark:bg-brand/20 text-brand dark:text-blue-300 hover:bg-brand/20 dark:hover:bg-brand/35 transition cursor-pointer">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/></svg>
                                    Edit
                                </button>
                                
                                <form method="POST" action="{{ route('admin.home-slides.destroy', $slide->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus slide ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold px-3.5 py-2 rounded-full border border-red-200 dark:border-red-900/30 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-300 hover:bg-red-100 dark:hover:bg-red-950/40 transition cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add/Edit Modal Slide --}}
    <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm" @click="modalOpen = false" x-transition.opacity></div>

        {{-- Container --}}
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative w-full max-w-xl bg-white dark:bg-gray-900 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-800 p-6 md:p-8 overflow-hidden transition-all duration-300" x-transition>
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white" x-text="editMode ? 'Perbarui Slide Homepage' : 'Tambah Slide Baru'"></h2>
                    <button @click="modalOpen = false" class="p-1.5 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form :action="formAction" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <template x-if="editMode">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    {{-- Urutan --}}
                    <div>
                        <label for="order" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Urutan Tampil</label>
                        <input type="number" id="order" name="order" x-model="slideOrder" required min="1"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-brand transition">
                    </div>

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Judul Slide (Gunakan &lt;br&gt; untuk baris baru)</label>
                        <textarea id="title" name="title" x-model="slideTitle" required rows="2"
                            placeholder="Contoh: Selamat Datang di<br><span class='text-accent'>MyKlinik911</span>"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-brand transition font-sans"></textarea>
                    </div>

                    {{-- Desc --}}
                    <div>
                        <label for="desc" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Penjelasan Singkat</label>
                        <textarea id="desc" name="desc" x-model="slideDesc" required rows="3"
                            placeholder="Deskripsi singkat slide..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-brand focus:border-brand transition"></textarea>
                    </div>

                    {{-- Image --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2" x-text="editMode ? 'Ganti Foto Background (Opsional)' : 'Pilih Foto Background'"></label>
                        
                        <div class="space-y-3">
                            {{-- Image preview --}}
                            <template x-if="slideImagePreview">
                                <div class="relative w-full h-32 rounded-xl overflow-hidden border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-950">
                                    <img :src="slideImagePreview" class="w-full h-full object-cover" alt="Selected background preview">
                                    <div class="absolute inset-0 bg-[#0F5D8C]/40"></div>
                                </div>
                            </template>

                            <input type="file" name="image" :required="!editMode" accept="image/*"
                                @change="
                                    const file = $event.target.files[0];
                                    if(file) {
                                        slideImagePreview = URL.createObjectURL(file);
                                    }
                                "
                                class="w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-brand-light dark:file:bg-brand/20 file:text-brand dark:file:text-blue-300 hover:file:bg-brand-light/80 dark:hover:file:bg-brand/30 file:cursor-pointer transition">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-5 mt-6">
                        <button type="button" @click="modalOpen = false"
                            class="px-5 py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold text-sm rounded-xl transition duration-200 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-brand hover:bg-brand-dark text-white font-semibold text-sm rounded-xl shadow-lg transition duration-200 cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
