<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    public function definition(): array
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $start = fake()->randomElement(['08:00', '09:00', '10:00', '13:00', '14:00']);
        $end = date('H:i', strtotime($start) + (rand(2, 4) * 3600)); // 2-4 hours duration

        return [
            'doctor_id' => Doctor::factory(),
            'day_of_week' => fake()->randomElement($days),
            'start_time' => $start,
            'end_time' => $end,
            'max_patients' => fake()->numberBetween(10, 30),
            'is_active' => true,
        ];
    }
}
