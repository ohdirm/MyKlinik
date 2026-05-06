<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),
            'specialization_id' => Specialization::factory(),
            'name' => 'Dr. ' . fake()->name(),
            'bio' => fake()->paragraph(),
            'is_active' => fake()->boolean(90), // 90% chance active
        ];
    }
}
