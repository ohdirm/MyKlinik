<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'];
        
        return [
            'user_id' => User::factory(),
            'schedule_id' => Schedule::factory(),
            'booking_date' => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'queue_number' => fake()->numberBetween(1, 20),
            'status' => fake()->randomElement($statuses),
            'notes' => fake()->sentence(),
        ];
    }
}
