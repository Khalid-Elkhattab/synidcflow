<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('un visiteur non authentifié ne peut pas accéder à la route admin', function () {
    $response = $this->getJson('/api/v1/admin-only');

    $response
        ->assertUnauthorized()
        ->assertJson([
            'message' => 'Unauthenticated.',
        ]);
});
test('un résident ne peut pas accéder à la route admin', function () {
    $resident = User::factory()->create([
        'role' => UserRole::Resident,
    ]);

    Sanctum::actingAs($resident);

    $response = $this->getJson('/api/v1/admin-only');

    $response
        ->assertForbidden()
        ->assertJson([
            'success' => false,
            'message' => 'Vous navez pas lautorisation nécessaire.',
        ]);
});
test('un administrateur peut accéder à la route admin', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    Sanctum::actingAs($admin);

    $response = $this->getJson('/api/v1/admin-only');

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Accès administrateur autorisé.',
        ]);
});
