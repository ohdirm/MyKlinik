@extends('layouts.app')

@section('title', 'Tambah Jadwal - Klinik App')
@section('header_title', 'Tambah Jadwal Praktik')

@section('content')
<div class="max-w-2xl bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
    <form action="{{ route('schedules.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">Dokter</label>
            <select id="doctor_id" name="doctor_id" required
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                <option value="">-- Pilih Dokter --</option>
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }} ({{ $doctor->clinic->name }})</option>
                @endforeach
            </select>
            @error('doctor_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="day_of_week" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
            <select id="day_of_week" name="day_of_week" required
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                <option value="">-- Pilih Hari --</option>
                <option value="monday" {{ old('day_of_week') == 'monday' ? 'selected' : '' }}>Senin</option>
                <option value="tuesday" {{ old('day_of_week') == 'tuesday' ? 'selected' : '' }}>Selasa</option>
                <option value="wednesday" {{ old('day_of_week') == 'wednesday' ? 'selected' : '' }}>Rabu</option>
                <option value="thursday" {{ old('day_of_week') == 'thursday' ? 'selected' : '' }}>Kamis</option>
                <option value="friday" {{ old('day_of_week') == 'friday' ? 'selected' : '' }}>Jumat</option>
                <option value="saturday" {{ old('day_of_week') == 'saturday' ? 'selected' : '' }}>Sabtu</option>
                <option value="sunday" {{ old('day_of_week') == 'sunday' ? 'selected' : '' }}>Minggu</option>
            </select>
            @error('day_of_week')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                @error('start_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required
                    class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
                @error('end_time')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label for="max_patients" class="block text-sm font-medium text-gray-700 mb-1">Kuota Pasien Maksimal</label>
            <input type="number" id="max_patients" name="max_patients" value="{{ old('max_patients', 20) }}" min="1" required
                class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 outline-none transition-all">
            @error('max_patients')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" checked
                    class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                <span class="text-sm font-medium text-gray-700">Jadwal Aktif</span>
            </label>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition-all focus:ring-4 focus:ring-indigo-100">
                Simpan Jadwal
            </button>
            <a href="{{ route('schedules.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-lg transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
