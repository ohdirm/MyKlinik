<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Clinic;
use App\Models\Specialization;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Create Patient User
        $patient = User::create([
            'name' => 'Pasien Budi',
            'email' => 'pasien@klinik.com',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);

        // 3. Seed Clinics
        $clinics = Clinic::factory(3)->create();

        // 4. Seed Specializations
        $specializations = [
            'Gigi dan Mulut',
            'Kandungan',
            'Anak',
            'Penyakit Dalam',
            'Mata'
        ];
        
        foreach ($specializations as $spec) {
            Specialization::create(['name' => $spec]);
        }
        $specs = Specialization::all();

        // 5. Seed Doctors & Schedules
        foreach ($clinics as $clinic) {
            // Tiap klinik punya 3 dokter
            for ($i = 0; $i < 3; $i++) {
                $doctor = Doctor::factory()->create([
                    'clinic_id' => $clinic->id,
                    'specialization_id' => $specs->random()->id,
                ]);

                // Tiap dokter punya 2 jadwal
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
                $selectedDays = fake()->randomElements($days, 2);

                foreach ($selectedDays as $day) {
                    Schedule::factory()->create([
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $day,
                    ]);
                }
            }
        }

        // 6. Seed some Bookings for the patient
        $schedules = Schedule::inRandomOrder()->take(5)->get();
        
        foreach ($schedules as $index => $schedule) {
            $status = $index === 0 ? 'completed' : ($index === 1 ? 'pending' : 'confirmed');
            
            $booking = Booking::create([
                'user_id' => $patient->id,
                'schedule_id' => $schedule->id,
                'booking_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                'status' => $status,
                'notes' => fake()->sentence(),
                'queue_number' => rand(1, 10)
            ]);

            // Add review for completed booking
            if ($status === 'completed') {
                Review::factory()->create([
                    'booking_id' => $booking->id,
                ]);
            }
        }
    }
}
