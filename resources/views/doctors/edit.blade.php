@extends('layouts.app')

@section('title', 'Edit Dokter - Klinik App')
@section('header_title', 'Edit Dokter')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
    <form action="{{ route('doctors.update', $doctor) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Dokter</label>
            <input type="text" id="name" name="name" value="{{ old('name', $doctor->name) }}" required
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
            @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="clinic_id" class="block text-sm font-medium text-gray-700 mb-1">Klinik</label>
                <select id="clinic_id" name="clinic_id" required
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                    <option value="">-- Pilih Klinik --</option>
                    @foreach($clinics as $clinic)
                        <option value="{{ $clinic->id }}" {{ old('clinic_id', $doctor->clinic_id) == $clinic->id ? 'selected' : '' }}>{{ $clinic->name }}</option>
                    @endforeach
                </select>
                @error('clinic_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="specialization_id" class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                <select id="specialization_id" name="specialization_id" required
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                    <option value="">-- Pilih Spesialisasi --</option>
                    @foreach($specializations as $spec)
                        <option value="{{ $spec->id }}" {{ old('specialization_id', $doctor->specialization_id) == $spec->id ? 'selected' : '' }}>{{ $spec->name }}</option>
                    @endforeach
                </select>
                @error('specialization_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio / Deskripsi (Opsional)</label>
            <textarea id="bio" name="bio" rows="4"
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">{{ old('bio', $doctor->bio) }}</textarea>
            @error('bio')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}
                    class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                <span class="text-sm font-medium text-gray-700">Dokter Aktif</span>
            </label>
            <p class="text-xs text-gray-500 mt-1">Hanya dokter aktif yang dapat menerima booking jadwal.</p>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
                Simpan Perubahan
            </button>
            <a href="{{ route('doctors.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
