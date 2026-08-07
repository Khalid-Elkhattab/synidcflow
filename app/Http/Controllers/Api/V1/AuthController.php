<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\Unauthenticated;

#[Group('Authentification')]
class AuthController extends Controller
{
    /**
     * Inscription d'un nouveau résident.
     */
    #[Unauthenticated]
    #[BodyParam('name', 'string', 'Nom complet de l\'utilisateur.', example: 'Jean Dupont')]
    #[BodyParam('email', 'string', 'Adresse e-mail (unique).', example: 'jean@exemple.fr')]
    #[BodyParam('password', 'string', 'Mot de passe (min 8, lettres, casse mixte, chiffres, symboles).', example: 'MotDePasse123!')]
    #[BodyParam('password_confirmation', 'string', 'Confirmation du mot de passe.', example: 'MotDePasse123!')]
    #[Response([
        'success' => true,
        'message' => 'compte cree avec sucess',
        'data' => [
            'user' => ['id' => 1, 'name' => 'Jean Dupont', 'email' => 'jean@exemple.fr', 'role' => 'resident'],
            'token' => '1|abc123token',
            'token_type' => 'Bearer',
        ],
    ], status: 201, description: 'Compte créé. Le rôle résident est toujours attribué.')]
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => UserRole::Resident,
        ]);
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'compte cree avec sucess',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],

        ], 201);
    }

    /**
     * Connexion d'un utilisateur existant.
     */
    #[Unauthenticated]
    #[BodyParam('email', 'string', 'Adresse e-mail du compte.', example: 'jean@exemple.fr')]
    #[BodyParam('password', 'string', 'Mot de passe du compte.', example: 'MotDePasse123!')]
    #[Response([
        'success' => true,
        'message' => 'Connexion réussie.',
        'data' => [
            'user' => ['id' => 1, 'name' => 'Jean Dupont', 'email' => 'jean@exemple.fr', 'role' => 'resident'],
            'token' => '1|abc123token',
            'token_type' => 'Bearer',
        ],
    ], description: 'Connexion réussie.')]
    #[Response([
        'success' => false,
        'message' => 'les informations fournit sont incorrects',
        'errors' => ['email' => ['les informations fournit sont incorrects']],
    ], status: 422, description: 'Identifiants incorrects.')]
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages(['email' => ['les informations fournit sont incorrects']]);
        }
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie.',
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ]);

    }

    /**
     * Profil de l'utilisateur authentifié.
     */
    #[Authenticated]
    #[Response([
        'success' => true,
        'message' => 'compte cree avec sucess',
        'data' => [
            'user' => ['id' => 1, 'name' => 'Jean Dupont', 'email' => 'jean@exemple.fr', 'role' => 'resident'],
        ],
    ], description: 'Utilisateur courant.')]
    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'compte cree avec sucess',
            'data' => [
                'user' => $request->user(),
            ],
        ]);

    }

    /**
     * Déconnexion : révoque le jeton courant.
     */
    #[Authenticated]
    #[Response([
        'success' => true,
        'message' => 'Déconnexion réussie.',
        'data' => null,
    ], description: 'Jeton révoqué.')]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.',
            'data' => null,
        ]);
    }
}
