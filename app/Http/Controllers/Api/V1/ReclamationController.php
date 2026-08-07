<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ReclamationStatut;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreReclamationRequest;
use App\Http\Requests\Api\V1\UpdateReclamationRequest;
use App\Http\Resources\ReclamationResource;
use App\Models\Reclamation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Réclamations')]
class ReclamationController extends Controller
{
    /**
     * Liste filtrée par rôle : résident = les siennes, syndic = celles de
     * ses résidences, admin = toutes.
     */
    #[Authenticated]
    #[Response([
        'success' => true,
        'message' => 'Réclamations récupérées avec succès.',
        'data' => [
            'reclamations' => [
                ['id' => 1, 'resident_id' => 3, 'appartement_id' => 1, 'titre' => 'Fuite d\'eau', 'description' => 'Fuite au niveau de la salle de bain.', 'statut' => 'submitted', 'priorite' => 'high', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
            ],
            'links' => ['first' => '/api/v1/reclamations?page=1', 'last' => '/api/v1/reclamations?page=1', 'prev' => null, 'next' => null],
            'meta' => ['current_page' => 1, 'from' => 1, 'last_page' => 1, 'path' => '/api/v1/reclamations', 'per_page' => 15, 'to' => 1, 'total' => 1],
        ],
    ], description: 'Liste paginée des réclamations visibles selon le rôle.')]
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $reclamations = Reclamation::query()
            ->with('appartement')
            ->when(
                $user->role === UserRole::Syndic,
                fn ($query) => $query->whereHas(
                    'appartement.immeuble.residence',
                    fn ($query) => $query->where('syndic_id', $user->id)
                )
            )
            ->when(
                $user->role === UserRole::Resident,
                fn ($query) => $query->where('resident_id', $user->id)
            )
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Réclamations récupérées avec succès.',
            'data' => [
                'reclamations' => ReclamationResource::collection($reclamations),
            ],
        ]);
    }

    /**
     * Crée une réclamation pour l'un des appartements du résident.
     */
    #[Authenticated]
    #[BodyParam('appartement_id', 'integer', 'Identifiant de l\'appartement (doit être affecté à l\'utilisateur).', example: 1)]
    #[BodyParam('titre', 'string', 'Titre de la réclamation.', example: 'Fuite d\'eau')]
    #[BodyParam('description', 'string', 'Description détaillée.', example: 'Fuite au niveau de la salle de bain.')]
    #[BodyParam('priorite', 'string', 'Priorité de la réclamation.', required: false, example: 'high', enum: ['low', 'medium', 'high', 'urgent'])]
    #[Response([
        'success' => true,
        'message' => 'Réclamation déposée avec succès.',
        'data' => [
            'reclamation' => ['id' => 1, 'resident_id' => 3, 'appartement_id' => 1, 'titre' => 'Fuite d\'eau', 'description' => 'Fuite au niveau de la salle de bain.', 'statut' => 'submitted', 'priorite' => 'high', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], status: 201, description: 'Réclamation créée avec le statut `submitted`.')]
    public function store(StoreReclamationRequest $request): JsonResponse
    {
        $reclamation = Reclamation::create([
            ...$request->validated(),
            'resident_id' => $request->user()->id,
            'statut' => ReclamationStatut::Submitted,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Réclamation déposée avec succès.',
            'data' => [
                'reclamation' => new ReclamationResource($reclamation),
            ],
        ], 201);
    }

    /**
     * Affiche une réclamation précise.
     */
    #[Authenticated]
    #[UrlParam('reclamation', 'integer', 'Identifiant de la réclamation.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Réclamation récupérée avec succès.',
        'data' => [
            'reclamation' => ['id' => 1, 'resident_id' => 3, 'appartement_id' => 1, 'titre' => 'Fuite d\'eau', 'description' => 'Fuite au niveau de la salle de bain.', 'statut' => 'submitted', 'priorite' => 'high', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Détail d\'une réclamation.')]
    public function show(Reclamation $reclamation): JsonResponse
    {
        $this->authorize('view', $reclamation);

        return response()->json([
            'success' => true,
            'message' => 'Réclamation récupérée avec succès.',
            'data' => [
                'reclamation' => new ReclamationResource($reclamation->loadMissing('appartement')),
            ],
        ]);
    }

    /**
     * Traitement par le syndic ou l'admin : évolution du statut.
     */
    #[Authenticated]
    #[UrlParam('reclamation', 'integer', 'Identifiant de la réclamation.', example: 1)]
    #[BodyParam('statut', 'string', 'Nouveau statut de la réclamation.', example: 'under_review', enum: ['submitted', 'under_review', 'accepted', 'rejected', 'resolved', 'closed'])]
    #[Response([
        'success' => true,
        'message' => 'Réclamation mise à jour avec succès.',
        'data' => [
            'reclamation' => ['id' => 1, 'resident_id' => 3, 'appartement_id' => 1, 'titre' => 'Fuite d\'eau', 'description' => 'Fuite au niveau de la salle de bain.', 'statut' => 'under_review', 'priorite' => 'high', 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-02T10:00:00.000000Z'],
        ],
    ], description: 'Réclamation traitée (statut mis à jour).')]
    public function update(UpdateReclamationRequest $request, Reclamation $reclamation): JsonResponse
    {
        $reclamation->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Réclamation mise à jour avec succès.',
            'data' => [
                'reclamation' => new ReclamationResource($reclamation),
            ],
        ]);
    }

    /**
     * Suppression réservée à l'admin.
     */
    #[Authenticated]
    #[UrlParam('reclamation', 'integer', 'Identifiant de la réclamation.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Réclamation supprimée avec succès.',
        'data' => null,
    ], description: 'Réclamation supprimée (soft delete). Réservé à l\'administrateur.')]
    public function destroy(Reclamation $reclamation): JsonResponse
    {
        $this->authorize('delete', $reclamation);

        $reclamation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Réclamation supprimée avec succès.',
            'data' => null,
        ]);
    }
}
