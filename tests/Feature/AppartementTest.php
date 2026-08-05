<?php

use App\Models\Appartement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('appartements', function () {
    test('un invité reçoit 401', function () {
        $chain = createChain();

        $this->getJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements")->assertUnauthorized();
        $this->postJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements", [
            'numero' => '202',
        ])->assertUnauthorized();
        $this->getJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}")->assertUnauthorized();
        $this->putJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}", [
            'numero' => '999',
        ])->assertUnauthorized();
        $this->deleteJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}")->assertUnauthorized();
    });

    test('un syndic gère les appartements de son immeuble', function () {
        $chain = createChain();

        Sanctum::actingAs($chain['syndic']);

        $this->getJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements")
            ->assertOk()
            ->assertJsonCount(1, 'data.appartements')
            ->assertJsonPath('data.appartements.0.statut', 'vacant');

        $this->postJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements", [
            'numero' => '202',
            'etage' => 2,
            'superficie' => 75.5,
        ])->assertCreated()
            ->assertJsonPath('data.appartement.statut', 'vacant');

        $this->putJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}", [
            'numero' => '101 bis',
        ])->assertOk()
            ->assertJsonPath('data.appartement.numero', '101 bis');

        $this->deleteJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}")
            ->assertOk();

        expect(Appartement::find($chain['appartement']->id))->toBeNull();
    });

    test('un syndic ne gère pas les appartements d\'un immeuble d\'un autre syndic', function () {
        $chain = createChain();
        $otherSyndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($otherSyndic);

        $this->postJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements", [
            'numero' => '202',
        ])->assertForbidden();

        $this->getJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}")
            ->assertForbidden();

        $this->putJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}", [
            'numero' => '999',
        ])->assertForbidden();

        $this->deleteJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}")
            ->assertForbidden();
    });

    test('un admin gère les appartements de n\'importe quel immeuble', function () {
        $chain = createChain();

        Sanctum::actingAs(User::factory()->asAdmin()->create());

        $this->postJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements", [
            'numero' => '202',
        ])->assertCreated();

        $this->deleteJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$chain['appartement']->id}")
            ->assertOk();
    });

    test('un résident ne voit que ses appartements (statut occupied)', function () {
        $chain = createChain();
        $resident = User::factory()->create();
        $otherResident = User::factory()->create();

        $own = $chain['appartement'];
        $own->update(['resident_id' => $resident->id]);

        $other = Appartement::create([
            'immeuble_id' => $chain['immeuble']->id,
            'numero' => '202',
            'resident_id' => $otherResident->id,
        ]);

        Sanctum::actingAs($resident);

        $this->getJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements")
            ->assertOk()
            ->assertJsonCount(1, 'data.appartements')
            ->assertJsonPath('data.appartements.0.id', $own->id)
            ->assertJsonPath('data.appartements.0.statut', 'occupied');

        $this->getJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$own->id}")
            ->assertOk();

        $this->getJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements/{$other->id}")
            ->assertForbidden();
    });

    test('un résident ne crée pas d\'appartement', function () {
        $chain = createChain();

        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements", [
            'numero' => '202',
        ])->assertForbidden();
    });

    test('le numéro est obligatoire et la superficie est positive', function () {
        $chain = createChain();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/immeubles/{$chain['immeuble']->id}/appartements", [
            'superficie' => -5,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['numero', 'superficie']);
    });
});
