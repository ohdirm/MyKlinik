<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specialization>
 */
class SpecializationFactory extends Factory
{
    public function definition(): array
    {
        $specializations = [
            'Gigi dan Mulut',
            'Kandungan',
            'Anak',
            'Penyakit Dalam',
            'Kulit dan Kelamin',
            'Mata',
            'THT',
            'Saraf',
            'Umum'
        ];

        return [
            'name' => fake()->unique()->randomElement($specializations),
        ];
    }
}
