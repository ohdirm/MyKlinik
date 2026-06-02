<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'profile_type' => ['required', Rule::in(['self', 'family'])],
            'family_profile_id' => ['required_if:profile_type,family', 'nullable', 'exists:family_profiles,id'],
            'patient_name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'phone' => ['required', 'string', 'regex:/^08[0-9]{8,11}$/'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:L,P'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'schedule_id' => ['required', 'exists:schedules,id'],
            'exam_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:'.now()->addDays(14)->toDateString()],
            'address' => ['required', 'string'],
            'province' => ['required', 'string'],
            'district' => ['required', 'string'],
            'sub_district' => ['required', 'string'],
            'village' => ['required', 'string'],
            'complaint' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nik.digits' => 'NIK harus 16 digit.',
            'phone.regex' => 'Format nomor HP tidak valid (contoh: 081234567890).',
            'exam_date.after' => 'Tanggal periksa harus setelah hari ini.',
            'exam_date.before_or_equal' => 'Tanggal periksa maksimal 14 hari ke depan.',
            'doctor_id.exists' => 'Dokter tidak ditemukan.',
            'schedule_id.exists' => 'Jadwal tidak ditemukan.',
            'profile_type.required' => 'Pilih tipe pasien (diri sendiri atau keluarga).',
            'profile_type.in' => 'Tipe pasien tidak valid.',
            'family_profile_id.required_if' => 'Pilih anggota keluarga yang akan didaftarkan.',
            'family_profile_id.exists' => 'Anggota keluarga tidak ditemukan.',
        ];
    }
}
