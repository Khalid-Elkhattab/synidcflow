<?php

use App\Models\Immeuble;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('assignation', function () {
    test('un invité reçoit 401', function () {
        $chain = createChain();

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => User::factory()->create()->id,
        ])->assertUnauthorized();

        $this->deleteJson("/api/v1/appartements/{$chain['appartement']->id}/assign")->assertUnauthorized();
    });

    test('un résident ne peut pas affecter un appartement', function () {
        $chain = createChain();

        Sanctum::actingAs(User::factory()->create());

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => User::factory()->create()->id,
        ])->assertForbidden();
    });

    test('un syndic affecte un résident existant à un appartement de sa résidence', function () {
        $chain = createChain();
        $resident = User::factory()->create();

        Sanctum::actingAs($chain['syndic']);

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => $resident->id,
        ])->assertOk()
            ->assertJsonPath('data.appartement.resident_id', $resident->id)
            ->assertJsonPath('data.appartement.statut', 'occupied');

        expect($chain['appartement']->fresh()->resident_id)->toBe($resident->id);
    });

    test('un admin affecte un résident à n\'importe quel appartement', function () {
        $chain = createChain();
        $resident = User::factory()->create();

        Sanctum::actingAs(User::factory()->asAdmin()->create());

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => $resident->id,
        ])->assertOk();
    });

    test('un non-résident ne peut pas être affecté (422)', function () {
        $chain = createChain();
        $syndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($chain['syndic']);

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => $syndic->id,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['resident_id']);
    });

    test('un utilisateur inexistant ne peut pas être affecté (422)', function () {
        $chain = createChain();

        Sanctum::actingAs($chain['syndic']);

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => 99999,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['resident_id']);
    });

    test('une nouvelle affectation remplace l\'ancienne', function () {
        $chain = createChain();
        $resident1 = User::factory()->create();
        $resident2 = User::factory()->create();

        Sanctum::actingAs($chain['syndic']);

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => $resident1->id,
        ])->assertOk();

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => $resident2->id,
        ])->assertOk()
            ->assertJsonPath('data.appartement.resident_id', $resident2->id);

        expect($chain['appartement']->fresh()->resident_id)->toBe($resident2->id);
    });

    test('un syndic ne peut pas affecter un appartement d\'un autre syndic', function () {
        $chain = createChain();
        $otherSyndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($otherSyndic);

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/assign", [
            'resident_id' => User::factory()->create()->id,
        ])->assertForbidden();

        $this->deleteJson("/api/v1/appartements/{$chain['appartement']->id}/assign")
            ->assertForbidden();
    });

    test('un syndic désaffecte le résident d\'un appartement', function () {
        $chain = createChain();
        $resident = User::factory()->create();
        $chain['appartement']->update(['resident_id' => $resident->id]);

        Sanctum::actingAs($chain['syndic']);

        $this->deleteJson("/api/v1/appartements/{$chain['appartement']->id}/assign")
            ->assertOk()
            ->assertJsonPath('data.appartement.resident_id', null)
            ->assertJsonPath('data.appartement.statut', 'vacant');

        expect($chain['appartement']->fresh()->resident_id)->toBeNull();
    });

    test('un résident accède à sa résidence via le chemin USER → APPARTEMENT → IMMEUBLE → RESIDENCE', function () {
        $chain = createChain();
        $resident = User::factory()->create();
        $chain['appartement']->update(['resident_id' => $resident->id]);

        $otherChain = createChain();

        Sanctum::actingAs($resident);

        $this->getJson('/api/v1/residences')
            ->assertOk()
            ->assertJsonCount(1, 'data.residences')
            ->assertJsonPath('data.residences.0.id', $chain['residence']->id);

        $this->getJson("/api/v1/residences/{$chain['residence']->id}")
            ->assertOk();

        $this->getJson("/api/v1/residences/{$otherChain['residence']->id}")
            ->assertForbidden();
    });

    test('un résident accède à son immeuble mais pas aux immeubles sans appartement', function () {
        $chain = createChain();
        $resident = User::factory()->create();
        $chain['appartement']->update(['resident_id' => $resident->id]);

        $immeubleSansAppartement = Immeuble::create([
            'residence_id' => $chain['residence']->id,
            'name' => 'Immeuble vide',
        ]);

        Sanctum::actingAs($resident);

        $this->getJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements")
            ->assertOk();

        $this->getJson("/api/v1/immeubles/{$immeubleSansAppartement->id}/appartements")
            ->assertForbidden();
    });
});
