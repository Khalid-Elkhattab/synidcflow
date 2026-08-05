<?php

namespace Database\Factories;

use App\Models\Appartement;
use App\Models\Immeuble;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appartement>
 */
class AppartementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'immeuble_id' => Immeuble::factory(),
            'numero' => (string) fake()->unique()->numberBetween(1, 500),
            'etage' => fake()->numberBetween(0, 15),
            'superficie' => fake()->randomFloat(2, 25, 250),
            'resident_id' => null,
        ];
    }

    /**
     * Indiquer que l'appartement est affecté à un résident.
     */
    public function forResident(mixed $resident): static
    {
        return $this->state(fn (array $attributes) => [
            'resident_id' => $resident,
        ]);
    }
}
