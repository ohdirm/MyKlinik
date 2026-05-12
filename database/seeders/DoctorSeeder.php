<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\DoctorStatus;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            ['name' => 'dr. Ahmad Fauzi', 'specialization' => 'UMUM', 'bio' => 'Dokter umum berpengalaman lebih dari 10 tahun di bidang pelayanan kesehatan primer.'],
            ['name' => 'dr. Siti Rahmawati', 'specialization' => 'UMUM', 'bio' => 'Dokter umum dengan keahlian dalam penanganan penyakit menular dan tidak menular.'],
            ['name' => 'dr. Budi Santoso, Sp.A', 'specialization' => 'SPESIALIS_ANAK', 'bio' => 'Spesialis anak dengan fokus pada tumbuh kembang dan imunisasi.'],
            ['name' => 'dr. Dewi Kartika, Sp.OG', 'specialization' => 'SPESIALIS_KANDUNGAN', 'bio' => 'Spesialis obstetri dan ginekologi untuk perawatan ibu dan anak.'],
            ['name' => 'dr. Hendra Wijaya, Sp.PD', 'specialization' => 'SPESIALIS_PENYAKIT_DALAM', 'bio' => 'Spesialis penyakit dalam dengan pengalaman menangani diabetes dan hipertensi.'],
            ['name' => 'dr. Rina Susanti, Sp.M', 'specialization' => 'SPESIALIS_MATA', 'bio' => 'Spesialis mata dengan keahlian dalam penanganan katarak dan glaukoma.'],
            ['name' => 'dr. Irfan Hakim, Sp.THT', 'specialization' => 'SPESIALIS_THT', 'bio' => 'Spesialis THT berpengalaman dalam penanganan gangguan pendengaran dan sinusitis.'],
        ];

        foreach ($doctors as $doctorData) {
            $doctor = Doctor::create(array_merge($doctorData, ['is_active' => true]));

            // Create schedules for Monday (1) to Friday (5), 08:00-12:00
            for ($day = 1; $day <= 5; $day++) {
                Schedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00:00',
                    'end_time' => '12:00:00',
                    'max_patients' => 20,
                ]);
            }

            // Create doctor status
            DoctorStatus::create([
                'doctor_id' => $doctor->id,
                'current_status' => 'AVAILABLE',
                'updated_at' => now(),
            ]);
        }
    }
}
