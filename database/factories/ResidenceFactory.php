<?php

namespace Database\Factories;

use App\Models\Residence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Residence>
 */
class ResidenceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'syndic_id' => User::factory()->asSyndic(),
            'name' => fake()->company().' Résidence',
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'description' => null,
        ];
    }
}
