<?php

use App\Models\Appartement;
use App\Models\Immeuble;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function createChain(?User $syndic = null): array
{
    $syndic ??= User::factory()->asSyndic()->create();
    $residence = createResidence(['syndic_id' => $syndic->id]);
    $immeuble = Immeuble::create([
        'residence_id' => $residence->id,
        'name' => 'Immeuble A',
        'nombre_etages' => 4,
    ]);
    $appartement = Appartement::create([
        'immeuble_id' => $immeuble->id,
        'numero' => '101',
    ]);

    return [
        'syndic' => $syndic,
        'residence' => $residence,
        'immeuble' => $immeuble,
        'appartement' => $appartement,
    ];
}

describe('immeubles', function () {
    test('un invité reçoit 401', function () {
        $chain = createChain();

        $this->getJson("/api/v1/residences/{$chain['residence']->id}/immeubles")->assertUnauthorized();
        $this->postJson("/api/v1/residences/{$chain['residence']->id}/immeubles", [
            'name' => 'Immeuble B',
        ])->assertUnauthorized();
        $this->getJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}")->assertUnauthorized();
        $this->putJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}", [
            'name' => 'Nouveau',
        ])->assertUnauthorized();
        $this->deleteJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}")->assertUnauthorized();
    });

    test('un syndic gère les immeubles de sa résidence', function () {
        $chain = createChain();

        Sanctum::actingAs($chain['syndic']);

        $this->getJson("/api/v1/residences/{$chain['residence']->id}/immeubles")
            ->assertOk()
            ->assertJsonCount(1, 'data.immeubles');

        $this->postJson("/api/v1/residences/{$chain['residence']->id}/immeubles", [
            'name' => 'Immeuble B',
            'nombre_etages' => 6,
        ])->assertCreated()
            ->assertJsonPath('data.immeuble.residence_id', $chain['residence']->id);

        $this->getJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}")
            ->assertOk()
            ->assertJsonPath('data.immeuble.name', 'Immeuble A');

        $this->putJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}", [
            'name' => 'Immeuble A modifié',
        ])->assertOk()
            ->assertJsonPath('data.immeuble.name', 'Immeuble A modifié');

        $this->deleteJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}")
            ->assertOk();

        expect(Immeuble::find($chain['immeuble']->id))->toBeNull();
        expect(Immeuble::withTrashed()->find($chain['immeuble']->id))->not->toBeNull();
    });

    test('un syndic ne gère pas les immeubles d\'une résidence qui n\'est pas la sienne', function () {
        $chain = createChain();
        $otherSyndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($otherSyndic);

        $this->postJson("/api/v1/residences/{$chain['residence']->id}/immeubles", [
            'name' => 'Immeuble B',
        ])->assertForbidden();

        $this->getJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}")
            ->assertForbidden();

        $this->putJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}", [
            'name' => 'Nouveau',
        ])->assertForbidden();

        $this->deleteJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}")
            ->assertForbidden();
    });

    test('un admin gère les immeubles de n\'importe quelle résidence', function () {
        $chain = createChain();

        Sanctum::actingAs(User::factory()->asAdmin()->create());

        $this->postJson("/api/v1/residences/{$chain['residence']->id}/immeubles", [
            'name' => 'Immeuble B',
        ])->assertCreated();

        $this->deleteJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}")
            ->assertOk();
    });

    test('un résident consulte uniquement les immeubles contenant ses appartements', function () {
        $chain = createChain();
        $resident = User::factory()->create();
        $chain['appartement']->update(['resident_id' => $resident->id]);

        $otherImmeuble = Immeuble::create([
            'residence_id' => $chain['residence']->id,
            'name' => 'Immeuble B',
        ]);

        Sanctum::actingAs($resident);

        $this->getJson("/api/v1/residences/{$chain['residence']->id}/immeubles")
            ->assertOk()
            ->assertJsonCount(1, 'data.immeubles')
            ->assertJsonPath('data.immeubles.0.id', $chain['immeuble']->id);

        $this->getJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$chain['immeuble']->id}")
            ->assertOk();

        $this->getJson("/api/v1/residences/{$chain['residence']->id}/immeubles/{$otherImmeuble->id}")
            ->assertForbidden();
    });

    test('un résident ne crée pas d\'immeuble', function () {
        $chain = createChain();

        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/v1/residences/{$chain['residence']->id}/immeubles", [
            'name' => 'Immeuble B',
        ])->assertForbidden();
    });

    test('le nom est obligatoire', function () {
        $chain = createChain();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/residences/{$chain['residence']->id}/immeubles", [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    });
});
