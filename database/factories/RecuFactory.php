<?php

namespace Database\Factories;

use App\Models\Charge;
use App\Models\Recu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recu>
 */
class RecuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'charge_id' => Charge::factory(),
            'fichier' => 'recus/'.fake()->uuid().'.jpg',
            'nom_original' => fake()->word().'.jpg',
            'type_mime' => 'image/jpeg',
            'taille' => fake()->numberBetween(1000, 5000000),
            'date_paiement' => fake()->date(),
            'montant_paye' => fake()->randomFloat(2, 50, 1500),
        ];
    }
}
