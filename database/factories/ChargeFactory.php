<?php

namespace Database\Factories;

use App\Enums\ChargeStatut;
use App\Models\Appartement;
use App\Models\Charge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Charge>
 */
class ChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'appartement_id' => Appartement::factory(),
            'libelle' => fake()->randomElement(['Charges de copropriété', 'Eau', 'Électricité', 'Ascenseur']),
            'description' => fake()->sentence(),
            'montant' => fake()->randomFloat(2, 50, 1500),
            'date_echeance' => fake()->date(),
            'statut' => ChargeStatut::Pending,
            'periode' => fake()->optional()->monthName(),
            'date_paiement' => null,
        ];
    }

    /**
     * Indiquer que la charge est payée.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => ChargeStatut::Paid,
            'date_paiement' => fake()->date(),
        ]);
    }

    /**
     * Indiquer que la charge est en retard.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => ChargeStatut::Overdue,
        ]);
    }
}
