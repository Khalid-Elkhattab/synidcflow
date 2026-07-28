<?php
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
test('un utilisateur peut créer un compte avec le rôle resident', function () {
    $response = $this->postJson('/api/v1/register',[
        'name'=>'Test Resident',
        'email'=> 'resident@example.com',
        'password'=>'Password123!',
        'password_confirmation' => 'Password123!',
    ]);
    
        $response
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.user.email', 'resident@example.com')
        ->assertJsonPath('data.user.role', UserRole::Resident->value)
        ->assertJsonStructure([
            'data' => [
                'user',
                'token',
                'token_type',
            ],
        ]);
   
          $this->assertDatabaseHas('users', [
        'email' => 'resident@example.com',
        'role' => UserRole::Resident->value,
    ]);
     $user = User::where('email', 'resident@example.com')->firstOrFail();
   expect($user->role)->toBe(UserRole::Resident);
});

test('un utilisateur peux se connecter avec des identifiants valid',function(){
$user = User::factory()->create([
    'email' => 'login@example.com',
    'password' => 'Password123!',
    'role' => UserRole::Resident,
]);

$response = $this->postJson('/api/v1/login',[
    'email'=>'login@example.com',
    'password' => 'Password123!',

]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.user.id', $user->id)
        ->assertJsonPath('data.user.email', 'login@example.com')
        ->assertJsonStructure([
            'data' => [
                'user',
                'token',
                'token_type',
            ],
        ]);

  expect($response->json('data.token'))->not->toBeEmpty();


});

test('un utilisateur authentifié peut consulter son profil',function(){
 $user=User::factory()->create([
    'role'=>UserRole::Resident,
 ]);

 $token=$user->createToken('test-token')->plainTextToken;
$response =$this->withToken('token')

});
