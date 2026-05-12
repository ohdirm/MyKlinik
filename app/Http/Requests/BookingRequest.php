<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'patient_name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'digits:16'],
            'phone' => ['required', 'string', 'max:15'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:L,P'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'schedule_id' => ['required', 'exists:schedules,id'],
            'exam_date' => ['required', 'date', 'after:today', 'before_or_equal:'.now()->addDays(14)->toDateString()],
            'address' => ['required', 'string'],
            'province' => ['required', 'string'],
            'district' => ['required', 'string'],
            'sub_district' => ['required', 'string'],
            'village' => ['required', 'string'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nik.digits' => 'NIK harus 16 digit.',
            'exam_date.after' => 'Tanggal periksa harus setelah hari ini.',
            'exam_date.before_or_equal' => 'Tanggal periksa maksimal 14 hari ke depan.',
            'doctor_id.exists' => 'Dokter tidak ditemukan.',
            'schedule_id.exists' => 'Jadwal tidak ditemukan.',
        ];
    }
}
