@extends('layouts.admin')
@section('title', 'Tambah Dokter — MyKlinik911')
@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">Tambah Dokter</h1>
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('admin.doctors.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" class="input-base" value="{{ old('name') }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                <select name="specialization" class="input-base" required>
                    <option value="">— Pilih —</option>
                    @foreach(['UMUM'=>'Umum','SPESIALIS_ANAK'=>'Spesialis Anak','SPESIALIS_KANDUNGAN'=>'Spesialis Kandungan','SPESIALIS_PENYAKIT_DALAM'=>'Spesialis Penyakit Dalam','SPESIALIS_BEDAH'=>'Spesialis Bedah','SPESIALIS_MATA'=>'Spesialis Mata','SPESIALIS_THT'=>'Spesialis THT','SPESIALIS_KULIT'=>'Spesialis Kulit','SPESIALIS_JANTUNG'=>'Spesialis Jantung'] as $val=>$label)
                        <option value="{{ $val }}" {{ old('specialization') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                <textarea name="bio" class="input-base" rows="3">{{ old('bio') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                <input type="file" name="photo" accept="image/*" class="input-base">
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-brand focus:ring-brand" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="text-sm text-gray-700">Aktif</label>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Simpan</button>
                <a href="{{ route('admin.doctors.index') }}" class="btn-outline">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
