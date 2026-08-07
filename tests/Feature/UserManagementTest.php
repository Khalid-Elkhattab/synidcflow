<?php

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('index', function () {
    test('un invité reçoit 401', function () {
        $this->getJson('/api/v1/users')->assertUnauthorized();
    });

    test('un syndic voit la liste des utilisateurs', function () {
        $syndic = User::factory()->asSyndic()->create();

        Sanctum::actingAs($syndic);

        $this->getJson('/api/v1/users')->assertOk();
    });

    test('un résident reçoit 403', function () {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/v1/users')->assertForbidden();
    });

    test('un admin voit la liste des utilisateurs', function () {
        $admin = User::factory()->asAdmin()->create();
        User::factory()->asSyndic()->create();
        User::factory()->create();

        Sanctum::actingAs($admin);

        $this->getJson('/api/v1/users')
            ->assertOk()
            ->assertJsonCount(3, 'data.users');
    });

    test('un admin peut filtrer par rôle', function () {
        $admin = User::factory()->asAdmin()->create();
        User::factory()->asSyndic()->create();
        User::factory()->create();

        Sanctum::actingAs($admin);

        $this->getJson('/api/v1/users?role=syndic')
            ->assertOk()
            ->assertJsonCount(1, 'data.users')
            ->assertJsonPath('data.users.0.role', 'syndic');
    });
});

describe('store', function () {
    test('un invité reçoit 401', function () {
        $this->postJson('/api/v1/users', [
            'name' => 'Nouveau',
            'email' => 'nouveau@example.com',
            'password' => 'password',
            'role' => 'resident',
        ])->assertUnauthorized();
    });

    test('un syndic reçoit 403', function () {
        Sanctum::actingAs(User::factory()->asSyndic()->create());

        $this->postJson('/api/v1/users', [
            'name' => 'Nouveau',
            'email' => 'nouveau@example.com',
            'password' => 'password',
            'role' => 'resident',
        ])->assertForbidden();
    });

    test('un résident reçoit 403', function () {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/users', [
            'name' => 'Nouveau',
            'email' => 'nouveau@example.com',
            'password' => 'password',
            'role' => 'resident',
        ])->assertForbidden();
    });

    test('un admin crée un compte pour n\'importe quel rôle', function () {
        Sanctum::actingAs(User::factory()->asAdmin()->create());

        foreach ([UserRole::Admin, UserRole::Syndic, UserRole::Resident] as $role) {
            $email = "{$role->value}.{$role->value}@example.com";

            $this->postJson('/api/v1/users', [
                'name' => 'Nouveau',
                'email' => $email,
                'password' => 'password',
                'role' => $role->value,
            ])->assertCreated()
                ->assertJsonPath('data.user.role', $role->value);
        }

        expect(User::count())->toBe(4);
    });

    test('un rôle invalide est refusé', function () {
        Sanctum::actingAs(User::factory()->asAdmin()->create());

        $this->postJson('/api/v1/users', [
            'name' => 'Nouveau',
            'email' => 'nouveau@example.com',
            'password' => 'password',
            'role' => 'superadmin',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['role']);
    });

    test('un email dupliqué est refusé avec 422', function () {
        $admin = User::factory()->asAdmin()->create();

        Sanctum::actingAs($admin);

        $this->postJson('/api/v1/users', [
            'name' => 'Nouveau',
            'email' => $admin->email,
            'password' => 'password',
            'role' => 'resident',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    });
});

describe('update', function () {
    test('un invité reçoit 401', function () {
        $user = User::factory()->create();

        $this->putJson("/api/v1/users/{$user->id}", [
            'name' => 'Modifié',
        ])->assertUnauthorized();
    });

    test('un résident reçoit 403', function () {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $this->putJson("/api/v1/users/{$user->id}", [
            'name' => 'Modifié',
        ])->assertForbidden();
    });

    test('un admin modifie le nom et le rôle d\'un compte', function () {
        $admin = User::factory()->asAdmin()->create();
        $user = User::factory()->asSyndic()->create();

        Sanctum::actingAs($admin);

        $this->putJson("/api/v1/users/{$user->id}", [
            'name' => 'Nom modifié',
            'role' => 'resident',
        ])->assertOk()
            ->assertJsonPath('data.user.name', 'Nom modifié')
            ->assertJsonPath('data.user.role', 'resident');

        expect($user->fresh()->role)->toBe(UserRole::Resident);
    });

    test('un admin peut modifier l\'email sans conflit avec l\'email courant', function () {
        $admin = User::factory()->asAdmin()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($admin);

        $this->putJson("/api/v1/users/{$user->id}", [
            'email' => $user->email,
        ])->assertOk();
    });

    test('un rôle invalide est refusé avec 422', function () {
        $admin = User::factory()->asAdmin()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($admin);

        $this->putJson("/api/v1/users/{$user->id}", [
            'role' => 'inexistant',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['role']);
    });
});
