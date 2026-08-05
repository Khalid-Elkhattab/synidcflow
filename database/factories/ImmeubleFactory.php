<?php

namespace Database\Factories;

use App\Models\Immeuble;
use App\Models\Residence;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Immeuble>
 */
class ImmeubleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'residence_id' => Residence::factory(),
            'name' => fake()->company().' Immeuble',
            'address' => fake()->streetAddress(),
            'nombre_etages' => fake()->numberBetween(2, 12),
        ];
    }
}
