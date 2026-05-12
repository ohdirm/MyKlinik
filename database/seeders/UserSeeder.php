<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@myklinik911.com',
            'password' => 'admin123',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Demo patients
        $patient1 = User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@example.com',
            'password' => 'password',
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);

        $patient2 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password',
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);

        $patient3 = User::create([
            'name' => 'Rina Kartika',
            'email' => 'rina@example.com',
            'password' => 'password',
            'role' => 'patient',
            'email_verified_at' => now(),
        ]);

        // Dummy reviews (clinic & doctor)
        $doctorIds = Doctor::pluck('id')->toArray();

        Review::create([
            'user_id' => $patient1->id,
            'type' => 'clinic',
            'rating' => 5,
            'comment' => 'Pelayanan klinik sangat memuaskan! Ruang tunggu bersih dan nyaman, staf ramah, dan proses pendaftaran online sangat memudahkan.',
        ]);

        Review::create([
            'user_id' => $patient2->id,
            'doctor_id' => $doctorIds[0] ?? null,
            'type' => 'doctor',
            'rating' => 5,
            'comment' => 'Dokter sangat teliti dalam memeriksa dan menjelaskan kondisi saya dengan detail. Sangat direkomendasikan!',
        ]);

        Review::create([
            'user_id' => $patient3->id,
            'type' => 'clinic',
            'rating' => 4,
            'comment' => 'Sistem antrean online sangat membantu. Tidak perlu menunggu lama di klinik. Semoga bisa tambahkan fitur resep online.',
        ]);

        Review::create([
            'user_id' => $patient1->id,
            'doctor_id' => $doctorIds[1] ?? $doctorIds[0] ?? null,
            'type' => 'doctor',
            'rating' => 4,
            'comment' => 'Dokternya baik dan sabar menjelaskan. Jadwal konsultasinya juga tepat waktu. Terima kasih!',
        ]);

        Review::create([
            'user_id' => $patient2->id,
            'type' => 'clinic',
            'rating' => 5,
            'comment' => 'MyKlinik911 adalah klinik terbaik yang pernah saya kunjungi. Modern, efisien, dan profesional.',
        ]);
    }
}
