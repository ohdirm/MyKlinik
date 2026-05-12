@extends('layouts.admin')
@section('title', 'Edit Jadwal — MyKlinik911')
@section('content')
<h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Jadwal</h1>
<div class="bg-white rounded-xl shadow-sm p-6 max-w-2xl">
    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"><ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
        @csrf @method('PUT')
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dokter</label>
                <select name="doctor_id" class="input-base" required>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ old('doctor_id', $schedule->doctor_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                <select name="day_of_week" class="input-base" required>
                    @foreach(['0'=>'Minggu','1'=>'Senin','2'=>'Selasa','3'=>'Rabu','4'=>'Kamis','5'=>'Jumat','6'=>'Sabtu'] as $v=>$l)
                        <option value="{{ $v }}" {{ old('day_of_week', $schedule->day_of_week) == $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Mulai</label>
                    <input type="time" name="start_time" class="input-base" value="{{ old('start_time', substr($schedule->start_time,0,5)) }}" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Selesai</label>
                    <input type="time" name="end_time" class="input-base" value="{{ old('end_time', substr($schedule->end_time,0,5)) }}" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Maks Pasien</label>
                <input type="number" name="max_patients" class="input-base" value="{{ old('max_patients', $schedule->max_patients) }}" min="1" required>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="btn-primary">Perbarui</button>
                <a href="{{ route('admin.schedules.index') }}" class="btn-outline">Batal</a>
            </div>
        </div>
    </form>
</div>
@endsection
