<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreResidenceRequest;
use App\Http\Requests\Api\V1\UpdateResidenceRequest;
use App\Http\Resources\ResidenceResource;
use App\Models\Residence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Résidences')]
class ResidenceController extends Controller
{
    /**
     * Liste filtrée par rôle : admin = toutes, syndic = les siennes,
     * résident = déduites de ses appartements.
     */
    #[Authenticated]
    #[Response([
        'success' => true,
        'message' => 'Résidences récupérées avec succès.',
        'data' => [
            'residences' => [
                ['id' => 1, 'syndic_id' => 2, 'name' => 'Résidence Les Oliviers', 'address' => '12 rue des Fleurs', 'city' => 'Casablanca', 'postal_code' => '20000', 'description' => null, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
            ],
            'links' => ['first' => '/api/v1/residences?page=1', 'last' => '/api/v1/residences?page=1', 'prev' => null, 'next' => null],
            'meta' => ['current_page' => 1, 'from' => 1, 'last_page' => 1, 'path' => '/api/v1/residences', 'per_page' => 15, 'to' => 1, 'total' => 1],
        ],
    ], description: 'Liste paginée filtrée par rôle.')]
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $residences = (match ($user->role) {
            UserRole::Admin => Residence::query()->latest(),
            UserRole::Syndic => Residence::query()
                ->where('syndic_id', $user->id)
                ->latest(),
            UserRole::Resident => Residence::query()
                ->whereHas(
                    'immeubles.appartements',
                    fn ($query) => $query->where('resident_id', $user->id)
                )
                ->latest(),
        })
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Résidences récupérées avec succès.',
            'data' => [
                'residences' => ResidenceResource::collection($residences),
            ],
        ]);
    }

    /**
     * Syndic : création pour lui-même. Admin : création pour un syndic choisi.
     */
    #[Authenticated]
    #[BodyParam('name', 'string', 'Nom de la résidence.', example: 'Résidence Les Oliviers')]
    #[BodyParam('address', 'string', 'Adresse.', example: '12 rue des Fleurs')]
    #[BodyParam('city', 'string', 'Ville.', example: 'Casablanca')]
    #[BodyParam('postal_code', 'string', 'Code postal.', required: false, example: '20000')]
    #[BodyParam('description', 'string', 'Description.', required: false, example: 'Résidence principale')]
    #[BodyParam('syndic_id', 'integer', 'Identifiant du syndic propriétaire (uniquement si l\'appelant est un administrateur).', required: false, example: 2)]
    #[Response([
        'success' => true,
        'message' => 'Résidence créée avec succès.',
        'data' => [
            'residence' => ['id' => 1, 'syndic_id' => 2, 'name' => 'Résidence Les Oliviers', 'address' => '12 rue des Fleurs', 'city' => 'Casablanca', 'postal_code' => '20000', 'description' => null, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], status: 201, description: 'Résidence créée. Le syndic devient automatiquement propriétaire pour un compte syndic.')]
    public function store(StoreResidenceRequest $request): JsonResponse
    {
        $user = $request->user();

        $residence = Residence::create([
            ...$request->validated(),
            'syndic_id' => $user->role === UserRole::Admin
                ? $request->validated('syndic_id')
                : $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Résidence créée avec succès.',
            'data' => [
                'residence' => new ResidenceResource($residence),
            ],
        ], 201);
    }

    /**
     * Affiche une résidence précise.
     */
    #[Authenticated]
    #[UrlParam('residence', 'integer', 'Identifiant de la résidence.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Résidence récupérée avec succès.',
        'data' => [
            'residence' => ['id' => 1, 'syndic_id' => 2, 'name' => 'Résidence Les Oliviers', 'address' => '12 rue des Fleurs', 'city' => 'Casablanca', 'postal_code' => '20000', 'description' => null, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Détail d\'une résidence.')]
    public function show(Residence $residence): JsonResponse
    {
        $this->authorize('view', $residence);

        return response()->json([
            'success' => true,
            'message' => 'Résidence récupérée avec succès.',
            'data' => [
                'residence' => new ResidenceResource($residence),
            ],
        ]);
    }

    /**
     * Met à jour une résidence.
     */
    #[Authenticated]
    #[UrlParam('residence', 'integer', 'Identifiant de la résidence.', example: 1)]
    #[BodyParam('name', 'string', 'Nom de la résidence.', required: false, example: 'Résidence Les Oliviers')]
    #[BodyParam('address', 'string', 'Adresse.', required: false, example: '12 rue des Fleurs')]
    #[BodyParam('city', 'string', 'Ville.', required: false, example: 'Casablanca')]
    #[BodyParam('postal_code', 'string', 'Code postal.', required: false, example: '20000')]
    #[BodyParam('description', 'string', 'Description.', required: false, example: 'Résidence principale')]
    #[Response([
        'success' => true,
        'message' => 'Résidence mise à jour avec succès.',
        'data' => [
            'residence' => ['id' => 1, 'syndic_id' => 2, 'name' => 'Résidence Les Oliviers', 'address' => '12 rue des Fleurs', 'city' => 'Casablanca', 'postal_code' => '20000', 'description' => null, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Résidence mise à jour.')]
    public function update(UpdateResidenceRequest $request, Residence $residence): JsonResponse
    {
        $residence->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Résidence mise à jour avec succès.',
            'data' => [
                'residence' => new ResidenceResource($residence),
            ],
        ]);
    }

    /**
     * Supprime (doucement) une résidence.
     */
    #[Authenticated]
    #[UrlParam('residence', 'integer', 'Identifiant de la résidence.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Résidence supprimée avec succès.',
        'data' => null,
    ], description: 'Résidence supprimée (soft delete).')]
    public function destroy(Residence $residence): JsonResponse
    {
        $this->authorize('delete', $residence);

        $residence->delete();

        return response()->json([
            'success' => true,
            'message' => 'Résidence supprimée avec succès.',
            'data' => null,
        ]);
    }
}
