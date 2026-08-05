<?php

use App\Enums\ReclamationStatut;
use App\Models\Reclamation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function createReclamationFor(?User $resident = null, ?array $chain = null): array
{
    $chain ??= createChain();
    $resident ??= User::factory()->create();
    $chain['appartement']->update(['resident_id' => $resident->id]);

    $reclamation = Reclamation::create([
        'resident_id' => $resident->id,
        'appartement_id' => $chain['appartement']->id,
        'titre' => 'Ascenseur en panne',
        'description' => "L'ascenseur ne fonctionne plus depuis trois jours.",
    ]);

    return [...$chain, 'resident' => $resident, 'reclamation' => $reclamation];
}

describe('réclamations', function () {
    test('un invité reçoit 401', function () {
        $chain = createReclamationFor();

        $this->getJson('/api/v1/reclamations')->assertUnauthorized();
        $this->postJson('/api/v1/reclamations', [
            'appartement_id' => $chain['appartement']->id,
            'titre' => 'Titre',
            'description' => 'Description',
        ])->assertUnauthorized();
        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}")->assertUnauthorized();
        $this->putJson("/api/v1/reclamations/{$chain['reclamation']->id}", [
            'statut' => 'under_review',
        ])->assertUnauthorized();
    });

    test('un résident dépose une réclamation pour l\'un de ses appartements', function () {
        $chain = createReclamationFor();

        Sanctum::actingAs($chain['resident']);

        $this->postJson('/api/v1/reclamations', [
            'appartement_id' => $chain['appartement']->id,
            'titre' => 'Fuite d\'eau',
            'description' => 'Une fuite est visible dans la salle de bain.',
            'priorite' => 'high',
        ])->assertCreated()
            ->assertJsonPath('data.reclamation.statut', 'submitted')
            ->assertJsonPath('data.reclamation.priorite', 'high')
            ->assertJsonPath('data.reclamation.resident_id', $chain['resident']->id);
    });

    test('un résident ne peut pas cibler l\'appartement d\'un autre résident (403)', function () {
        $chain = createReclamationFor();
        $otherResident = User::factory()->create();

        Sanctum::actingAs($otherResident);

        $this->postJson('/api/v1/reclamations', [
            'appartement_id' => $chain['appartement']->id,
            'titre' => 'Titre',
            'description' => 'Description',
        ])->assertForbidden();
    });

    test('un résident ne peut pas cibler un appartement vacant (403)', function () {
        $chain = createChain();
        $resident = User::factory()->create();

        Sanctum::actingAs($resident);

        $this->postJson('/api/v1/reclamations', [
            'appartement_id' => $chain['appartement']->id,
            'titre' => 'Titre',
            'description' => 'Description',
        ])->assertForbidden();
    });

    test('un syndic ne peut pas déposer une réclamation (403)', function () {
        $chain = createReclamationFor();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson('/api/v1/reclamations', [
            'appartement_id' => $chain['appartement']->id,
            'titre' => 'Titre',
            'description' => 'Description',
        ])->assertForbidden();
    });

    test('un résident ne voit que ses réclamations', function () {
        $chain = createReclamationFor();
        $otherResident = User::factory()->create();

        Sanctum::actingAs($otherResident);

        $this->getJson('/api/v1/reclamations')
            ->assertOk()
            ->assertJsonCount(0, 'data.reclamations');

        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}")
            ->assertForbidden();

        Sanctum::actingAs($chain['resident']);

        $this->getJson('/api/v1/reclamations')
            ->assertOk()
            ->assertJsonCount(1, 'data.reclamations');
    });

    test('un syndic voit et traite les réclamations de ses résidences', function () {
        $chain = createReclamationFor();

        Sanctum::actingAs($chain['syndic']);

        $this->getJson('/api/v1/reclamations')
            ->assertOk()
            ->assertJsonCount(1, 'data.reclamations');

        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}")
            ->assertOk()
            ->assertJsonPath('data.reclamation.statut', 'submitted');

        $this->putJson("/api/v1/reclamations/{$chain['reclamation']->id}", [
            'statut' => 'under_review',
        ])->assertOk()
            ->assertJsonPath('data.reclamation.statut', 'under_review');

        expect($chain['reclamation']->fresh()->statut)->toBe(ReclamationStatut::UnderReview);
    });

    test('un syndic ne voit ni ne traite les réclamations d\'un autre syndic', function () {
        $chain = createReclamationFor();
        $otherSyndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($otherSyndic);

        $this->getJson('/api/v1/reclamations')
            ->assertOk()
            ->assertJsonCount(0, 'data.reclamations');

        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}")
            ->assertForbidden();

        $this->putJson("/api/v1/reclamations/{$chain['reclamation']->id}", [
            'statut' => 'accepted',
        ])->assertForbidden();
    });

    test('un résident suit le statut mais ne modifie pas sa réclamation', function () {
        $chain = createReclamationFor();
        $chain['reclamation']->update(['statut' => ReclamationStatut::UnderReview]);

        Sanctum::actingAs($chain['resident']);

        $this->getJson("/api/v1/reclamations/{$chain['reclamation']->id}")
            ->assertOk()
            ->assertJsonPath('data.reclamation.statut', 'under_review');

        $this->putJson("/api/v1/reclamations/{$chain['reclamation']->id}", [
            'statut' => 'accepted',
        ])->assertForbidden();
    });

    test('un admin voit, traite et supprime n\'importe quelle réclamation', function () {
        $chain = createReclamationFor();

        Sanctum::actingAs(User::factory()->asAdmin()->create());

        $this->getJson('/api/v1/reclamations')
            ->assertOk()
            ->assertJsonCount(1, 'data.reclamations');

        $this->putJson("/api/v1/reclamations/{$chain['reclamation']->id}", [
            'statut' => 'closed',
        ])->assertOk();

        $this->deleteJson("/api/v1/reclamations/{$chain['reclamation']->id}")
            ->assertOk();

        expect(Reclamation::find($chain['reclamation']->id))->toBeNull();
    });

    test('un statut invalide est refusé (422)', function () {
        $chain = createReclamationFor();

        Sanctum::actingAs($chain['syndic']);

        $this->putJson("/api/v1/reclamations/{$chain['reclamation']->id}", [
            'statut' => 'inexistant',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['statut']);
    });

    test('une priorité invalide est refusée (422)', function () {
        $chain = createReclamationFor();

        Sanctum::actingAs($chain['resident']);

        $this->postJson('/api/v1/reclamations', [
            'appartement_id' => $chain['appartement']->id,
            'titre' => 'Titre',
            'description' => 'Description',
            'priorite' => 'critique',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['priorite']);
    });
});
