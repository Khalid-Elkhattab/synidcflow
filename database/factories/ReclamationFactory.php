<?php

namespace Database\Factories;

use App\Enums\ReclamationPriorite;
use App\Enums\ReclamationStatut;
use App\Models\Appartement;
use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reclamation>
 */
class ReclamationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $resident = User::factory()->create();
        $appartement = Appartement::factory()->forResident($resident)->create();

        return [
            'resident_id' => $resident->id,
            'appartement_id' => $appartement->id,
            'titre' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'statut' => ReclamationStatut::Submitted,
            'priorite' => ReclamationPriorite::Medium,
        ];
    }

    /**
     * Indiquer le statut de la réclamation.
     */
    public function withStatut(ReclamationStatut $statut): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => $statut,
        ]);
    }
}
