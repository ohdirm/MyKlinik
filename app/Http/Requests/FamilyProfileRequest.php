<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FamilyProfileRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:255'],
            'relationship' => ['required', 'in:Ayah,Ibu,Anak,Saudara,Suami/Istri,Lainnya'],
            'nik' => ['required', 'digits:16'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:L,P'],
            'phone_number' => ['required', 'string', 'regex:/^(08|\\+62)[0-9]{8,13}$/', 'max:15'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'relationship.required' => 'Hubungan keluarga wajib dipilih.',
            'relationship.in' => 'Hubungan keluarga tidak valid.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus 16 digit angka.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'phone_number.required' => 'Nomor HP/WhatsApp wajib diisi.',
            'phone_number.regex' => 'Format nomor HP tidak valid. Gunakan format 08xx atau +62xx.',
        ];
    }
}
