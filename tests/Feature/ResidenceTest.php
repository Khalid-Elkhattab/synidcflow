<?php

use App\Models\Residence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

function createResidence(array $attributes = []): Residence
{
    return Residence::create([
        'syndic_id' => $attributes['syndic_id'] ?? User::factory()->asSyndic()->create()->id,
        'name' => $attributes['name'] ?? 'Résidence Al Amal',
        'address' => $attributes['address'] ?? '10 rue Mohammed V',
        'city' => $attributes['city'] ?? 'Casablanca',
        'postal_code' => $attributes['postal_code'] ?? '20000',
        'description' => $attributes['description'] ?? null,
    ]);
}

test('une résidence appartient à un syndic', function () {
    $syndic = User::factory()->asSyndic()->create();

    $residence = createResidence(['syndic_id' => $syndic->id]);

    expect($residence->syndic)
        ->toBeInstanceOf(User::class)
        ->id->toBe($syndic->id);
});

test('un syndic peut gérer plusieurs résidences', function () {
    $syndic = User::factory()->asSyndic()->create();

    createResidence(['syndic_id' => $syndic->id]);
    createResidence([
        'syndic_id' => $syndic->id,
        'name' => 'Résidence Anfa',
        'address' => '20 boulevard Anfa',
    ]);

    expect($syndic->residences)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Residence::class);
});

describe('index', function () {
    test('un invité reçoit 401', function () {
        $this->getJson('/api/v1/residences')->assertUnauthorized();
    });

    test('un admin voit toutes les résidences', function () {
        $admin = User::factory()->asAdmin()->create();
        createResidence();
        createResidence();

        Sanctum::actingAs($admin);

        $this->getJson('/api/v1/residences')
            ->assertOk()
            ->assertJsonCount(2, 'data.residences');
    });

    test('un syndic ne voit que ses résidences', function () {
        $syndic = User::factory()->asSyndic()->create();
        $otherSyndic = User::factory()->asSyndic()->create();
        createResidence(['syndic_id' => $syndic->id, 'name' => 'La mienne']);
        createResidence(['syndic_id' => $otherSyndic->id, 'name' => "Celle de l'autre"]);

        Sanctum::actingAs($syndic);

        $this->getJson('/api/v1/residences')
            ->assertOk()
            ->assertJsonCount(1, 'data.residences')
            ->assertJsonPath('data.residences.0.name', 'La mienne');
    });

    test('un résident obtient une liste vide sans appartement affecté', function () {
        $resident = User::factory()->create();
        createResidence();

        Sanctum::actingAs($resident);

        $this->getJson('/api/v1/residences')
            ->assertOk()
            ->assertJsonCount(0, 'data.residences');
    });
});

describe('store', function () {
    test('un invité reçoit 401', function () {
        $this->postJson('/api/v1/residences', [
            'name' => 'Résidence Test',
            'address' => '1 rue Test',
            'city' => 'Rabat',
        ])->assertUnauthorized();
    });

    test('un résident reçoit 403', function () {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/residences', [
            'name' => 'Résidence Test',
            'address' => '1 rue Test',
            'city' => 'Rabat',
        ])->assertForbidden();
    });

    test('un syndic crée une résidence pour lui-même', function () {
        $syndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($syndic);

        $this->postJson('/api/v1/residences', [
            'name' => 'Résidence Test',
            'address' => '1 rue Test',
            'city' => 'Rabat',
        ])->assertCreated()
            ->assertJsonPath('data.residence.syndic_id', $syndic->id);

        expect(Residence::count())->toBe(1);
    });

    test('un admin crée une résidence pour un syndic choisi', function () {
        $admin = User::factory()->asAdmin()->create();
        $syndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($admin);

        $this->postJson('/api/v1/residences', [
            'name' => 'Résidence Test',
            'address' => '1 rue Test',
            'city' => 'Rabat',
            'syndic_id' => $syndic->id,
        ])->assertCreated()
            ->assertJsonPath('data.residence.syndic_id', $syndic->id);
    });

    test("un admin ne peut pas choisir un syndic_id qui n'est pas un syndic", function () {
        $admin = User::factory()->asAdmin()->create();
        $resident = User::factory()->create();

        Sanctum::actingAs($admin);

        $this->postJson('/api/v1/residences', [
            'name' => 'Résidence Test',
            'address' => '1 rue Test',
            'city' => 'Rabat',
            'syndic_id' => $resident->id,
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['syndic_id']);
    });

    test('un admin doit fournir un syndic_id', function () {
        Sanctum::actingAs(User::factory()->asAdmin()->create());

        $this->postJson('/api/v1/residences', [
            'name' => 'Résidence Test',
            'address' => '1 rue Test',
            'city' => 'Rabat',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['syndic_id']);
    });

    test('la validation des champs obligatoires est respectée', function () {
        Sanctum::actingAs(User::factory()->asSyndic()->create());

        $this->postJson('/api/v1/residences', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'address', 'city']);
    });
});

describe('show', function () {
    test('un invité reçoit 401', function () {
        $residence = createResidence();

        $this->getJson("/api/v1/residences/{$residence->id}")->assertUnauthorized();
    });

    test('un syndic consulte sa propre résidence', function () {
        $syndic = User::factory()->asSyndic()->create();
        $residence = createResidence(['syndic_id' => $syndic->id]);

        Sanctum::actingAs($syndic);

        $this->getJson("/api/v1/residences/{$residence->id}")
            ->assertOk()
            ->assertJsonPath('data.residence.id', $residence->id);
    });

    test('un syndic ne consulte pas la résidence d\'un autre syndic', function () {
        $syndic = User::factory()->asSyndic()->create();
        $otherSyndic = User::factory()->asSyndic()->create();
        $residence = createResidence(['syndic_id' => $otherSyndic->id]);

        Sanctum::actingAs($syndic);

        $this->getJson("/api/v1/residences/{$residence->id}")->assertForbidden();
    });

    test('un admin consulte n\'importe quelle résidence', function () {
        $admin = User::factory()->asAdmin()->create();
        $residence = createResidence();

        Sanctum::actingAs($admin);

        $this->getJson("/api/v1/residences/{$residence->id}")
            ->assertOk()
            ->assertJsonPath('data.residence.id', $residence->id);
    });

    test('un résident reçoit 403', function () {
        $resident = User::factory()->create();
        $residence = createResidence();

        Sanctum::actingAs($resident);

        $this->getJson("/api/v1/residences/{$residence->id}")->assertForbidden();
    });
});

describe('update', function () {
    test('un invité reçoit 401', function () {
        $residence = createResidence();

        $this->putJson("/api/v1/residences/{$residence->id}", [
            'name' => 'Nouveau nom',
        ])->assertUnauthorized();
    });

    test('un syndic modifie sa propre résidence', function () {
        $syndic = User::factory()->asSyndic()->create();
        $residence = createResidence(['syndic_id' => $syndic->id]);

        Sanctum::actingAs($syndic);

        $this->putJson("/api/v1/residences/{$residence->id}", [
            'name' => 'Nouveau nom',
        ])->assertOk()
            ->assertJsonPath('data.residence.name', 'Nouveau nom');
    });

    test('un syndic ne modifie pas la résidence d\'un autre syndic', function () {
        $syndic = User::factory()->asSyndic()->create();
        $otherSyndic = User::factory()->asSyndic()->create();
        $residence = createResidence(['syndic_id' => $otherSyndic->id]);

        Sanctum::actingAs($syndic);

        $this->putJson("/api/v1/residences/{$residence->id}", [
            'name' => 'Nouveau nom',
        ])->assertForbidden();
    });

    test('un admin modifie n\'importe quelle résidence', function () {
        $admin = User::factory()->asAdmin()->create();
        $residence = createResidence();

        Sanctum::actingAs($admin);

        $this->putJson("/api/v1/residences/{$residence->id}", [
            'name' => 'Nouveau nom',
        ])->assertOk()
            ->assertJsonPath('data.residence.name', 'Nouveau nom');
    });

    test('un résident reçoit 403', function () {
        $resident = User::factory()->create();
        $residence = createResidence();

        Sanctum::actingAs($resident);

        $this->putJson("/api/v1/residences/{$residence->id}", [
            'name' => 'Nouveau nom',
        ])->assertForbidden();
    });
});

describe('destroy', function () {
    test('un invité reçoit 401', function () {
        $residence = createResidence();

        $this->deleteJson("/api/v1/residences/{$residence->id}")->assertUnauthorized();
    });

    test('un syndic supprime sa propre résidence (soft delete)', function () {
        $syndic = User::factory()->asSyndic()->create();
        $residence = createResidence(['syndic_id' => $syndic->id]);

        Sanctum::actingAs($syndic);

        $this->deleteJson("/api/v1/residences/{$residence->id}")->assertOk();

        expect(Residence::withTrashed()->find($residence->id))->not->toBeNull();
        expect(Residence::find($residence->id))->toBeNull();
    });

    test('un syndic ne supprime pas la résidence d\'un autre syndic', function () {
        $syndic = User::factory()->asSyndic()->create();
        $otherSyndic = User::factory()->asSyndic()->create();
        $residence = createResidence(['syndic_id' => $otherSyndic->id]);

        Sanctum::actingAs($syndic);

        $this->deleteJson("/api/v1/residences/{$residence->id}")->assertForbidden();
    });

    test('un admin supprime n\'importe quelle résidence', function () {
        $admin = User::factory()->asAdmin()->create();
        $residence = createResidence();

        Sanctum::actingAs($admin);

        $this->deleteJson("/api/v1/residences/{$residence->id}")->assertOk();

        expect(Residence::find($residence->id))->toBeNull();
    });

    test('un résident reçoit 403', function () {
        $resident = User::factory()->create();
        $residence = createResidence();

        Sanctum::actingAs($resident);

        $this->deleteJson("/api/v1/residences/{$residence->id}")->assertForbidden();
    });
});
