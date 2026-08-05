<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ChargeStatut;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\MarkChargePaidRequest;
use App\Http\Requests\Api\V1\StoreChargeRequest;
use App\Http\Requests\Api\V1\UpdateChargeRequest;
use App\Http\Resources\ChargeResource;
use App\Models\Appartement;
use App\Models\Charge;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChargeController extends Controller
{
    /**
     * Liste des charges d'un appartement accessible.
     */
    public function index(Request $request, Appartement $appartement): JsonResponse
    {
        $this->authorize('view', $appartement);

        $charges = $appartement->charges()
            ->with('recu')
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Charges récupérées avec succès.',
            'data' => [
                'charges' => ChargeResource::collection($charges),
            ],
        ]);
    }

    /**
     * Crée une charge (statut initial : pending, doc §12).
     */
    public function store(StoreChargeRequest $request, Appartement $appartement): JsonResponse
    {
        $charge = Charge::create([
            ...$request->validated(),
            'appartement_id' => $appartement->id,
            'statut' => ChargeStatut::Pending,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Charge créée avec succès.',
            'data' => [
                'charge' => new ChargeResource($charge),
            ],
        ], 201);
    }

    /**
     * Affiche une charge précise.
     */
    public function show(Appartement $appartement, Charge $charge): JsonResponse
    {
        $this->authorize('view', $charge);

        return response()->json([
            'success' => true,
            'message' => 'Charge récupérée avec succès.',
            'data' => [
                'charge' => new ChargeResource($charge->loadMissing('recu')),
            ],
        ]);
    }

    /**
     * Met à jour une charge (hors statut, géré par markAsPaid).
     */
    public function update(UpdateChargeRequest $request, Appartement $appartement, Charge $charge): JsonResponse
    {
        $charge->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Charge mise à jour avec succès.',
            'data' => [
                'charge' => new ChargeResource($charge->loadMissing('recu')),
            ],
        ]);
    }

    /**
     * Supprime (doucement) une charge.
     */
    public function destroy(Appartement $appartement, Charge $charge): JsonResponse
    {
        $this->authorize('delete', $charge);

        $charge->delete();

        return response()->json([
            'success' => true,
            'message' => 'Charge supprimée avec succès.',
            'data' => null,
        ]);
    }

    /**
     * Déclare manuellement le paiement : pending → paid (doc §12).
     */
    public function markAsPaid(MarkChargePaidRequest $request, Charge $charge): JsonResponse
    {
        if ($charge->statut === ChargeStatut::Paid) {
            return response()->json([
                'success' => false,
                'message' => 'Cette charge est déjà marquée comme payée.',
                'data' => null,
            ], 409);
        }

        $charge->update([
            'statut' => ChargeStatut::Paid,
            'date_paiement' => $request->validated('date_paiement') ?? today()->toDateString(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Charge marquée comme payée avec succès.',
            'data' => [
                'charge' => new ChargeResource($charge->loadMissing('recu')),
            ],
        ]);
    }
}
