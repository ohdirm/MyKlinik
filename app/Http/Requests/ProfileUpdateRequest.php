<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
            'nik' => ['required', 'digits:16'],
            'full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:L,P'],
            'phone_number' => ['required', 'string', 'regex:/^(08|\\+62)[0-9]{8,13}$/', 'max:15'],
            'address' => ['required', 'string', 'max:500'],
            'province' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'sub_district' => ['required', 'string', 'max:255'],
            'village' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus 16 digit angka.',
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin tidak valid.',
            'phone_number.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'phone_number.regex' => 'Format nomor HP tidak valid. Gunakan format 08xx atau +62xx.',
            'address.required' => 'Alamat lengkap wajib diisi.',
            'province.required' => 'Provinsi wajib dipilih.',
            'district.required' => 'Kabupaten/Kota wajib dipilih.',
            'sub_district.required' => 'Kecamatan wajib dipilih.',
            'village.required' => 'Kelurahan/Desa wajib dipilih.',
        ];
    }
}
