<?php

use App\Enums\ChargeStatut;
use App\Models\Charge;
use App\Models\Recu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function createPaidChain(?User $syndic = null): array
{
    $chain = createChain($syndic);
    $charge = Charge::create([
        'appartement_id' => $chain['appartement']->id,
        'libelle' => 'Charges de copropriété',
        'montant' => 250.00,
        'date_echeance' => '2026-08-31',
        'statut' => ChargeStatut::Paid,
        'date_paiement' => '2026-08-01',
    ]);

    return [...$chain, 'charge' => $charge];
}

describe('reçus', function () {
    test('un invité reçoit 401', function () {
        Storage::fake('private');
        $chain = createPaidChain();

        $this->postJson("/api/v1/charges/{$chain['charge']->id}/recus", [
            'fichier' => UploadedFile::fake()->create('recu.jpg', 100, 'image/jpeg'),
            'date_paiement' => '2026-08-01',
            'montant_paye' => 250.00,
        ])->assertUnauthorized();
    });

    test('un syndic téléverse un reçu JPG sur une charge payée', function () {
        Storage::fake('private');
        $chain = createPaidChain();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/charges/{$chain['charge']->id}/recus", [
            'fichier' => UploadedFile::fake()->create('recu.jpg', 100, 'image/jpeg'),
            'date_paiement' => '2026-08-01',
            'montant_paye' => 250.00,
        ])->assertCreated()
            ->assertJsonPath('data.recu.type_mime', 'image/jpeg');

        expect(Recu::count())->toBe(1);

        $fichier = Recu::first()->fichier;
        Storage::disk('private')->assertExists($fichier);
    });

    test('un reçu est refusé si la charge n\'est pas payée (422)', function () {
        Storage::fake('private');
        $chain = createChain();
        $charge = Charge::create([
            'appartement_id' => $chain['appartement']->id,
            'libelle' => 'Charges',
            'montant' => 100,
            'date_echeance' => '2026-08-31',
            'statut' => ChargeStatut::Pending,
        ]);

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/charges/{$charge->id}/recus", [
            'fichier' => UploadedFile::fake()->create('recu.jpg', 100, 'image/jpeg'),
            'date_paiement' => '2026-08-01',
            'montant_paye' => 100,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['fichier']);
    });

    test('un second reçu actif est refusé (422)', function () {
        Storage::fake('private');
        $chain = createPaidChain();
        Recu::create([
            'charge_id' => $chain['charge']->id,
            'fichier' => 'recus/existant.jpg',
            'nom_original' => 'existant.jpg',
            'type_mime' => 'image/jpeg',
            'taille' => 1000,
            'date_paiement' => '2026-08-01',
            'montant_paye' => 250,
        ]);

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/charges/{$chain['charge']->id}/recus", [
            'fichier' => UploadedFile::fake()->create('recu.jpg', 100, 'image/jpeg'),
            'date_paiement' => '2026-08-02',
            'montant_paye' => 250,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['fichier']);
    });

    test('un format non autorisé (PDF) est refusé (422)', function () {
        Storage::fake('private');
        $chain = createPaidChain();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/charges/{$chain['charge']->id}/recus", [
            'fichier' => UploadedFile::fake()->create('recu.pdf', 100, 'application/pdf'),
            'date_paiement' => '2026-08-01',
            'montant_paye' => 250,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['fichier']);
    });

    test('un résident affecté consulte et télécharge le reçu', function () {
        Storage::fake('private');
        $chain = createPaidChain();
        $resident = User::factory()->create();
        $chain['appartement']->update(['resident_id' => $resident->id]);

        $recu = Recu::create([
            'charge_id' => $chain['charge']->id,
            'fichier' => 'recus/recu.jpg',
            'nom_original' => 'recu.jpg',
            'type_mime' => 'image/jpeg',
            'taille' => 1000,
            'date_paiement' => '2026-08-01',
            'montant_paye' => 250,
        ]);
        Storage::disk('private')->put('recus/recu.jpg', 'contenu-jpg');

        Sanctum::actingAs($resident);

        $this->getJson("/api/v1/recus/{$recu->id}")
            ->assertOk()
            ->assertJsonPath('data.recu.charge_id', $chain['charge']->id);

        $this->get("/api/v1/recus/{$recu->id}/download")
            ->assertOk()
            ->assertHeader('Content-Disposition', 'attachment; filename=recu.jpg');
    });

    test('un résident non affecté reçoit 403 sur le reçu', function () {
        Storage::fake('private');
        $chain = createPaidChain();
        $otherResident = User::factory()->create();

        $recu = Recu::create([
            'charge_id' => $chain['charge']->id,
            'fichier' => 'recus/recu.jpg',
            'nom_original' => 'recu.jpg',
            'type_mime' => 'image/jpeg',
            'taille' => 1000,
            'date_paiement' => '2026-08-01',
            'montant_paye' => 250,
        ]);

        Sanctum::actingAs($otherResident);

        $this->getJson("/api/v1/recus/{$recu->id}")->assertForbidden();
        $this->get("/api/v1/recus/{$recu->id}/download")->assertForbidden();
    });

    test('un reçu supprimé (soft) peut être remplacé par un nouveau', function () {
        Storage::fake('private');
        $chain = createPaidChain();

        $recu = Recu::create([
            'charge_id' => $chain['charge']->id,
            'fichier' => 'recus/ancien.jpg',
            'nom_original' => 'ancien.jpg',
            'type_mime' => 'image/jpeg',
            'taille' => 1000,
            'date_paiement' => '2026-08-01',
            'montant_paye' => 250,
        ]);
        $recu->delete();

        Sanctum::actingAs($chain['syndic']);

        $this->postJson("/api/v1/charges/{$chain['charge']->id}/recus", [
            'fichier' => UploadedFile::fake()->create('recu.jpg', 100, 'image/jpeg'),
            'date_paiement' => '2026-08-02',
            'montant_paye' => 250,
        ])->assertCreated();

        expect(Recu::withTrashed()->count())->toBe(1);
        expect(Recu::count())->toBe(1);
        expect(Recu::first()->nom_original)->toBe('recu.jpg');
    });
});
