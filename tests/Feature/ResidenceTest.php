<?php

use App\Models\Residence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('une résidence appartient à un syndic', function () {
    $syndic = User::factory()->create();

    $residence = Residence::create([
        'syndic_id' => $syndic->id,
        'name' => 'Résidence Al Amal',
        'address' => '10 rue Mohammed V',
        'city' => 'Casablanca',
        'postal_code' => '20000',
        'description' => 'Résidence de test',
    ]);

    expect($residence->syndic)
        ->toBeInstanceOf(User::class)
        ->id->toBe($syndic->id);
});

test('un syndic peut gérer plusieurs résidences', function () {
    $syndic = User::factory()->create();

    Residence::create([
        'syndic_id' => $syndic->id,
        'name' => 'Résidence Al Amal',
        'address' => '10 rue Mohammed V',
        'city' => 'Casablanca',
    ]);

    Residence::create([
        'syndic_id' => $syndic->id,
        'name' => 'Résidence Anfa',
        'address' => '20 boulevard Anfa',
        'city' => 'Casablanca',
    ]);

    expect($syndic->residences)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Residence::class);
});
