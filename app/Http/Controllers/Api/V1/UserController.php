<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Utilisateurs')]
class UserController extends Controller
{
    /**
     * Liste paginée des utilisateurs, filtrable par rôle.
     */
    #[Authenticated]
    #[QueryParam('role', 'string', 'Filtre sur le rôle (admin, syndic, resident).', required: false, example: 'syndic')]
    #[Response([
        'success' => true,
        'message' => 'Utilisateurs récupérés avec succès.',
        'data' => [
            'users' => [
                ['id' => 1, 'name' => 'Jean Dupont', 'email' => 'jean@exemple.fr', 'role' => 'syndic', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
            ],
            'links' => ['first' => '/api/v1/users?page=1', 'last' => '/api/v1/users?page=1', 'prev' => null, 'next' => null],
            'meta' => ['current_page' => 1, 'from' => 1, 'last_page' => 1, 'links' => [], 'path' => '/api/v1/users', 'per_page' => 15, 'to' => 1, 'total' => 1],
        ],
    ], description: 'Liste paginée. Accès réservé à l\'admin ou au syndic.')]
    public function index(Request $request): JsonResponse
    {
        $users = User::query()
            ->when($request->filled('role'), fn ($query) => $query->where('role', $request->input('role')))
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Utilisateurs récupérés avec succès.',
            'data' => [
                'users' => UserResource::collection($users),
            ],
        ]);
    }

    /**
     * Crée un compte avec le rôle choisi (admin, syndic ou résident).
     */
    #[Authenticated]
    #[BodyParam('name', 'string', 'Nom complet de l\'utilisateur.', example: 'Marie Martin')]
    #[BodyParam('email', 'string', 'Adresse e-mail (unique).', example: 'marie@exemple.fr')]
    #[BodyParam('password', 'string', 'Mot de passe (min 8 caractères).', example: 'MotDePasse123!')]
    #[BodyParam('role', 'string', 'Rôle à attribuer.', example: 'syndic', enum: ['admin', 'syndic', 'resident'])]
    #[Response([
        'success' => true,
        'message' => 'Utilisateur créé avec succès.',
        'data' => [
            'user' => ['id' => 2, 'name' => 'Marie Martin', 'email' => 'marie@exemple.fr', 'role' => 'syndic', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], status: 201, description: 'Compte créé. Réservé à l\'administrateur.')]
    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès.',
            'data' => [
                'user' => new UserResource($user),
            ],
        ], 201);
    }

    /**
     * Modifie un compte, y compris son rôle.
     */
    #[Authenticated]
    #[UrlParam('user', 'integer', 'Identifiant de l\'utilisateur.', example: 2)]
    #[BodyParam('name', 'string', 'Nom complet de l\'utilisateur.', required: false, example: 'Marie Martin')]
    #[BodyParam('email', 'string', 'Adresse e-mail (unique, ignore l\'utilisateur courant).', required: false, example: 'marie@exemple.fr')]
    #[BodyParam('role', 'string', 'Nouveau rôle à attribuer.', required: false, example: 'resident', enum: ['admin', 'syndic', 'resident'])]
    #[Response([
        'success' => true,
        'message' => 'Utilisateur mis à jour avec succès.',
        'data' => [
            'user' => ['id' => 2, 'name' => 'Marie Martin', 'email' => 'marie@exemple.fr', 'role' => 'admin', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Compte modifié. Réservé à l\'administrateur.')]
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès.',
            'data' => [
                'user' => new UserResource($user),
            ],
        ]);
    }
}
