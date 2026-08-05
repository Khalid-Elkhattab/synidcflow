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

class ReclamationController extends Controller
{
    /**
     * Liste filtrée par rôle : résident = les siennes, syndic = celles de
     * ses résidences, admin = toutes.
     */
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
