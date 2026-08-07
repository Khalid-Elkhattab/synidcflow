<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreImmeubleRequest;
use App\Http\Requests\Api\V1\UpdateImmeubleRequest;
use App\Http\Resources\ImmeubleResource;
use App\Models\Immeuble;
use App\Models\Residence;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Immeubles')]
class ImmeubleController extends Controller
{
    /**
     * Liste des immeubles d'une résidence, filtrée par rôle.
     */
    #[Authenticated]
    #[UrlParam('residence', 'integer', 'Identifiant de la résidence.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Immeubles récupérés avec succès.',
        'data' => [
            'immeubles' => [
                ['id' => 1, 'residence_id' => 1, 'name' => 'Bâtiment A', 'address' => '12 rue des Fleurs', 'nombre_etages' => 5, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
            ],
            'links' => ['first' => '/api/v1/residences/1/immeubles?page=1', 'last' => '/api/v1/residences/1/immeubles?page=1', 'prev' => null, 'next' => null],
            'meta' => ['current_page' => 1, 'from' => 1, 'last_page' => 1, 'path' => '/api/v1/residences/1/immeubles', 'per_page' => 15, 'to' => 1, 'total' => 1],
        ],
    ], description: 'Liste paginée des immeubles d\'une résidence.')]
    public function index(Request $request, Residence $residence): JsonResponse
    {
        $this->authorize('view', $residence);

        $user = $request->user();

        $immeubles = Immeuble::query()
            ->where('residence_id', $residence->id)
            ->when(
                $user->role === UserRole::Resident,
                fn ($query) => $query->whereHas(
                    'appartements',
                    fn ($query) => $query->where('resident_id', $user->id)
                )
            )
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Immeubles récupérés avec succès.',
            'data' => [
                'immeubles' => ImmeubleResource::collection($immeubles),
            ],
        ]);
    }

    /**
     * Crée un immeuble dans la résidence courante.
     */
    #[Authenticated]
    #[UrlParam('residence', 'integer', 'Identifiant de la résidence.', example: 1)]
    #[BodyParam('name', 'string', 'Nom de l\'immeuble.', example: 'Bâtiment A')]
    #[BodyParam('address', 'string', 'Adresse de l\'immeuble.', required: false, example: '12 rue des Fleurs')]
    #[BodyParam('nombre_etages', 'integer', 'Nombre d\'étages.', required: false, example: 5)]
    #[Response([
        'success' => true,
        'message' => 'Immeuble créé avec succès.',
        'data' => [
            'immeuble' => ['id' => 1, 'residence_id' => 1, 'name' => 'Bâtiment A', 'address' => '12 rue des Fleurs', 'nombre_etages' => 5, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], status: 201, description: 'Immeuble créé.')]
    public function store(StoreImmeubleRequest $request, Residence $residence): JsonResponse
    {
        $immeuble = Immeuble::create([
            ...$request->validated(),
            'residence_id' => $residence->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Immeuble créé avec succès.',
            'data' => [
                'immeuble' => new ImmeubleResource($immeuble),
            ],
        ], 201);
    }

    /**
     * Affiche un immeuble précis.
     */
    #[Authenticated]
    #[UrlParam('residence', 'integer', 'Identifiant de la résidence.', example: 1)]
    #[UrlParam('immeuble', 'integer', 'Identifiant de l\'immeuble.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Immeuble récupéré avec succès.',
        'data' => [
            'immeuble' => ['id' => 1, 'residence_id' => 1, 'name' => 'Bâtiment A', 'address' => '12 rue des Fleurs', 'nombre_etages' => 5, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Détail d\'un immeuble.')]
    public function show(Residence $residence, Immeuble $immeuble): JsonResponse
    {
        $this->authorize('view', $immeuble);

        return response()->json([
            'success' => true,
            'message' => 'Immeuble récupéré avec succès.',
            'data' => [
                'immeuble' => new ImmeubleResource($immeuble),
            ],
        ]);
    }

    /**
     * Met à jour un immeuble.
     */
    #[Authenticated]
    #[UrlParam('residence', 'integer', 'Identifiant de la résidence.', example: 1)]
    #[UrlParam('immeuble', 'integer', 'Identifiant de l\'immeuble.', example: 1)]
    #[BodyParam('name', 'string', 'Nom de l\'immeuble.', required: false, example: 'Bâtiment A')]
    #[BodyParam('address', 'string', 'Adresse de l\'immeuble.', required: false, example: '12 rue des Fleurs')]
    #[BodyParam('nombre_etages', 'integer', 'Nombre d\'étages.', required: false, example: 5)]
    #[Response([
        'success' => true,
        'message' => 'Immeuble mis à jour avec succès.',
        'data' => [
            'immeuble' => ['id' => 1, 'residence_id' => 1, 'name' => 'Bâtiment A', 'address' => '12 rue des Fleurs', 'nombre_etages' => 6, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Immeuble mis à jour.')]
    public function update(UpdateImmeubleRequest $request, Residence $residence, Immeuble $immeuble): JsonResponse
    {
        $immeuble->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Immeuble mis à jour avec succès.',
            'data' => [
                'immeuble' => new ImmeubleResource($immeuble),
            ],
        ]);
    }

    /**
     * Supprime (doucement) un immeuble.
     */
    #[Authenticated]
    #[UrlParam('residence', 'integer', 'Identifiant de la résidence.', example: 1)]
    #[UrlParam('immeuble', 'integer', 'Identifiant de l\'immeuble.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Immeuble supprimé avec succès.',
        'data' => null,
    ], description: 'Immeuble supprimé (soft delete).')]
    public function destroy(Residence $residence, Immeuble $immeuble): JsonResponse
    {
        $this->authorize('delete', $immeuble);

        $immeuble->delete();

        return response()->json([
            'success' => true,
            'message' => 'Immeuble supprimé avec succès.',
            'data' => null,
        ]);
    }
}
