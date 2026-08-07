<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AssignResidentRequest;
use App\Http\Requests\Api\V1\StoreAppartementRequest;
use App\Http\Requests\Api\V1\UpdateAppartementRequest;
use App\Http\Resources\AppartementResource;
use App\Models\Appartement;
use App\Models\Immeuble;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Appartements')]
class AppartementController extends Controller
{
    /**
     * Liste des appartements d'un immeuble, filtrée par rôle.
     */
    #[Authenticated]
    #[UrlParam('immeuble', 'integer', 'Identifiant de l\'immeuble.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Appartements récupérés avec succès.',
        'data' => [
            'appartements' => [
                ['id' => 1, 'immeuble_id' => 1, 'resident_id' => null, 'numero' => 'A1', 'etage' => 1, 'superficie' => '85.00', 'statut' => 'vacant', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
            ],
            'links' => ['first' => '/api/v1/immeubles/1/appartements?page=1', 'last' => '/api/v1/immeubles/1/appartements?page=1', 'prev' => null, 'next' => null],
            'meta' => ['current_page' => 1, 'from' => 1, 'last_page' => 1, 'path' => '/api/v1/immeubles/1/appartements', 'per_page' => 15, 'to' => 1, 'total' => 1],
        ],
    ], description: 'Liste paginée des appartements. Un résident ne voit que les siens.')]
    public function index(Request $request, Immeuble $immeuble): JsonResponse
    {
        $this->authorize('view', $immeuble);

        $user = $request->user();

        $appartements = Appartement::query()
            ->where('immeuble_id', $immeuble->id)
            ->when(
                $user->role === UserRole::Resident,
                fn ($query) => $query->where('resident_id', $user->id)
            )
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Appartements récupérés avec succès.',
            'data' => [
                'appartements' => AppartementResource::collection($appartements),
            ],
        ]);
    }

    /**
     * Crée un appartement dans l'immeuble courant.
     */
    #[Authenticated]
    #[UrlParam('immeuble', 'integer', 'Identifiant de l\'immeuble.', example: 1)]
    #[BodyParam('numero', 'string', 'Numéro de l\'appartement (unique dans l\'immeuble).', example: 'A')]
    #[BodyParam('etage', 'integer', 'Étage.', required: false, example: 1)]
    #[BodyParam('superficie', 'number', 'Superficie en m².', required: false, example: 85)]
    #[Response([
        'success' => true,
        'message' => 'Appartement créé avec succès.',
        'data' => [
            'appartement' => ['id' => 1, 'immeuble_id' => 1, 'resident_id' => null, 'numero' => 'A', 'etage' => 1, 'superficie' => '85.00', 'statut' => 'vacant', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], status: 201, description: 'Appartement créé.')]
    public function store(StoreAppartementRequest $request, Immeuble $immeuble): JsonResponse
    {
        $appartement = Appartement::create([
            ...$request->validated(),
            'immeuble_id' => $immeuble->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appartement créé avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement),
            ],
        ], 201);
    }

    /**
     * Affiche un appartement précis.
     */
    #[Authenticated]
    #[UrlParam('immeuble', 'integer', 'Identifiant de l\'immeuble.', example: 1)]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Appartement récupéré avec succès.',
        'data' => [
            'appartement' => ['id' => 1, 'immeuble_id' => 1, 'resident_id' => 3, 'numero' => 'A', 'etage' => 1, 'superficie' => '85.00', 'statut' => 'occupé', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Détail d\'un appartement.')]
    public function show(Immeuble $immeuble, Appartement $appartement): JsonResponse
    {
        $this->authorize('view', $appartement);

        return response()->json([
            'success' => true,
            'message' => 'Appartement récupéré avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement),
            ],
        ]);
    }

    /**
     * Met à jour un appartement.
     */
    #[Authenticated]
    #[UrlParam('immeuble', 'integer', 'Identifiant de l\'immeuble.', example: 1)]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[BodyParam('numero', 'string', 'Numéro de l\'appartement.', required: false, example: 'A')]
    #[BodyParam('etage', 'integer', 'Étage.', required: false, example: 1)]
    #[BodyParam('superficie', 'number', 'Superficie en m².', required: false, example: 85.00)]
    #[Response([
        'success' => true,
        'message' => 'Appartement mis à jour avec succès.',
        'data' => [
            'appartement' => ['id' => 1, 'immeuble_id' => 1, 'resident_id' => 3, 'numero' => 'A', 'etage' => 2, 'superficie' => '85.00', 'statut' => 'occupé', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Appartement mis à jour.')]
    public function update(UpdateAppartementRequest $request, Immeuble $immeuble, Appartement $appartement): JsonResponse
    {
        $appartement->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Appartement mis à jour avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement),
            ],
        ]);
    }

    /**
     * Supprime (doucement) un appartement.
     */
    #[Authenticated]
    #[UrlParam('immeuble', 'integer', 'Identifiant de l\'immeuble.', example: 1)]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Appartement supprimé avec succès.',
        'data' => null,
    ], description: 'Appartement supprimé (soft delete).')]
    public function destroy(Immeuble $immeuble, Appartement $appartement): JsonResponse
    {
        $this->authorize('delete', $appartement);

        $appartement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Appartement supprimé avec succès.',
            'data' => null,
        ]);
    }

    /**
     * Affecte un résident existant à un appartement vacant (transaction).
     */
    #[Authenticated]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[BodyParam('resident_id', 'integer', 'Identifiant d\'un utilisateur ayant le rôle résident.', example: 3)]
    #[Response([
        'success' => true,
        'message' => 'Résident affecté avec succès.',
        'data' => [
            'appartement' => ['id' => 1, 'immeuble_id' => 1, 'resident_id' => 3, 'numero' => 'A', 'etage' => 1, 'superficie' => '85.00', 'statut' => 'occupé', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Résident affecté. Une affectation existante est remplacée.')]
    public function assign(AssignResidentRequest $request, Appartement $appartement): JsonResponse
    {
        DB::transaction(function () use ($request, $appartement): void {
            $appartement->update([
                'resident_id' => $request->validated('resident_id'),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Résident affecté avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement->fresh()),
            ],
        ]);
    }

    /**
     * Désaffecte le résident courant de l'appartement.
     */
    #[Authenticated]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Résident désaffecté avec succès.',
        'data' => [
            'appartement' => ['id' => 1, 'immeuble_id' => 1, 'resident_id' => null, 'numero' => 'A', 'etage' => 1, 'superficie' => '85.00', 'statut' => 'vacant', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Résident désaffecté (appartement vacant).')]
    public function deassign(Request $request, Appartement $appartement): JsonResponse
    {
        $this->authorize('assign', $appartement);

        $appartement->update([
            'resident_id' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Résident désaffecté avec succès.',
            'data' => [
                'appartement' => new AppartementResource($appartement->fresh()),
            ],
        ]);
    }
}
