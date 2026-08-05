<?php

use App\Enums\ChargeStatut;
use App\Models\Charge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function createChainWithCharge(?User $syndic = null): array
{
    $chain = createChain($syndic);
    $charge = Charge::create([
        'appartement_id' => $chain['appartement']->id,
        'libelle' => 'Charges de copropriété',
        'montant' => 250.00,
        'date_echeance' => '2026-08-31',
    ]);

    return [...$chain, 'charge' => $charge];
}

describe('charges', function () {
    test('un invité reçoit 401', function () {
        $chain = createChainWithCharge();

        $this->getJson("/api/v1/appartements/{$chain['appartement']->id}/charges")->assertUnauthorized();
        $this->postJson("/api/v1/appartements/{$chain['appartement']->id}/charges", [
            'libelle' => 'Eau',
            'montant' => 100,
            'date_echeance' => '2026-09-30',
        ])->assertUnauthorized();
        $this->getJson("/api/v1/appartements/{$chain['appartement']->id}/charges/{$chain['charge']->id}")->assertUnauthorized();
        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/charges/{$chain['charge']->id}", [
            'libelle' => 'Modifié',
        ])->assertUnauthorized();
        $this->deleteJson("/api/v1/appartements/{$chain['appartement']->id}/charges/{$chain['charge']->id}")->assertUnauthorized();
        $this->patchJson("/api/v1/charges/{$chain['charge']->id}/payer")->assertUnauthorized();
    });

    test('un syndic gère les charges de ses appartements', function () {
        $chain = createChainWithCharge();

        Sanctum::actingAs($chain['syndic']);

        $this->getJson("/api/v1/appartements/{$chain['appartement']->id}/charges")
            ->assertOk()
            ->assertJsonCount(1, 'data.charges')
            ->assertJsonPath('data.charges.0.statut', 'pending');

        $this->postJson("/api/v1/appartements/{$chain['appartement']->id}/charges", [
            'libelle' => 'Eau',
            'montant' => 120.50,
            'date_echeance' => '2026-09-30',
            'periode' => 'Septembre',
        ])->assertCreated()
            ->assertJsonPath('data.charge.statut', 'pending')
            ->assertJsonPath('data.charge.montant', '120.50');

        $this->getJson("/api/v1/appartements/{$chain['appartement']->id}/charges/{$chain['charge']->id}")
            ->assertOk()
            ->assertJsonPath('data.charge.libelle', 'Charges de copropriété');

        $this->putJson("/api/v1/appartements/{$chain['appartement']->id}/charges/{$chain['charge']->id}", [
            'libelle' => 'Charges modifiées',
        ])->assertOk()
            ->assertJsonPath('data.charge.libelle', 'Charges modifiées');

        $this->deleteJson("/api/v1/appartements/{$chain['appartement']->id}/charges/{$chain['charge']->id}")
            ->assertOk();

        expect(Charge::find($chain['charge']->id))->toBeNull();
    });

    test('un syndic ne gère pas les charges d\'un appartement d\'un autre syndic', function () {
        $chain = createChainWithCharge();
        $otherSyndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($otherSyndic);

        $this->postJson("/api/v1/appartements/{$chain['appartement']->id}/charges", [
            'libelle' => 'Eau',
            'montant' => 100,
            'date_echeance' => '2026-09-30',
        ])->assertForbidden();

        $this->getJson("/api/v1/appartements/{$chain['appartement']->id}/charges/{$chain['charge']->id}")
            ->assertForbidden();

        $this->patchJson("/api/v1/charges/{$chain['charge']->id}/payer")
            ->assertForbidden();
    });

    test('un résident consulte les charges de ses appartements mais ne les modifie pas', function () {
        $chain = createChainWithCharge();
        $resident = User::factory()->create();
        $chain['appartement']->update(['resident_id' => $resident->id]);

        Sanctum::actingAs($resident);

        $this->getJson("/api/v1/appartements/{$chain['appartement']->id}/charges")
            ->assertOk()
            ->assertJsonCount(1, 'data.charges');

        $this->getJson("/api/v1/appartements/{$chain['appartement']->id}/charges/{$chain['charge']->id}")
            ->assertOk();

        $this->postJson("/api/v1/appartements/{$chain['appartement']->id}/charges", [
            'libelle' => 'Eau',
            'montant' => 100,
            'date_echeance' => '2026-09-30',
        ])->assertForbidden();

        $this->patchJson("/api/v1/charges/{$chain['charge']->id}/payer")
            ->assertForbidden();
    });

    test('la validation des montants est respectée', function () {
        $chain = createChainWithCharge();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/appartements/{$chain['appartement']->id}/charges", [
            'libelle' => 'Eau',
            'montant' => -5,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['montant', 'date_echeance']);
    });

    test('le paiement est déclaré manuellement : pending → paid', function () {
        $chain = createChainWithCharge();

        Sanctum::actingAs($chain['syndic']);

        $this->patchJson("/api/v1/charges/{$chain['charge']->id}/payer")
            ->assertOk()
            ->assertJsonPath('data.charge.statut', 'paid');

        expect($chain['charge']->fresh()->statut)->toBe(ChargeStatut::Paid);
        expect($chain['charge']->fresh()->date_paiement->toDateString())->toBe(today()->toDateString());
    });

    test('une charge déjà payée ne peut pas être marquée payée à nouveau (409)', function () {
        $chain = createChainWithCharge();
        $chain['charge']->update([
            'statut' => ChargeStatut::Paid,
            'date_paiement' => today(),
        ]);

        Sanctum::actingAs($chain['syndic']);

        $this->patchJson("/api/v1/charges/{$chain['charge']->id}/payer")
            ->assertStatus(409);
    });
});
