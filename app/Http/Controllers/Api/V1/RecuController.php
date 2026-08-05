<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreRecuRequest;
use App\Http\Resources\RecuResource;
use App\Models\Charge;
use App\Models\Recu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecuController extends Controller
{
    /**
     * Téléverse un reçu scanné (JPG/PNG) pour une charge payée.
     */
    public function store(StoreRecuRequest $request, Charge $charge): JsonResponse
    {
        $recu = DB::transaction(function () use ($request, $charge): Recu {
            Recu::withTrashed()
                ->where('charge_id', $charge->id)
                ->forceDelete();

            $fichier = $request->file('fichier');

            $recu = Recu::create([
                'charge_id' => $charge->id,
                'fichier' => $fichier->store('recus', 'private'),
                'nom_original' => $fichier->getClientOriginalName(),
                'type_mime' => $fichier->getMimeType(),
                'taille' => $fichier->getSize(),
                'date_paiement' => $request->validated('date_paiement'),
                'montant_paye' => $request->validated('montant_paye'),
            ]);

            return $recu;
        });

        return response()->json([
            'success' => true,
            'message' => 'Reçu téléversé avec succès.',
            'data' => [
                'recu' => new RecuResource($recu),
            ],
        ], 201);
    }

    /**
     * Affiche les métadonnées d'un reçu.
     */
    public function show(Recu $recu): JsonResponse
    {
        $this->authorize('view', $recu);

        return response()->json([
            'success' => true,
            'message' => 'Reçu récupéré avec succès.',
            'data' => [
                'recu' => new RecuResource($recu),
            ],
        ]);
    }

    /**
     * Télécharge le fichier scanné (accès autorisé uniquement).
     */
    public function download(Recu $recu): StreamedResponse|JsonResponse
    {
        $this->authorize('view', $recu);

        if (! Storage::disk('private')->exists($recu->fichier)) {
            return response()->json([
                'success' => false,
                'message' => 'Le fichier du reçu est introuvable.',
                'data' => null,
            ], 404);
        }

        return Storage::disk('private')->response($recu->fichier, $recu->nom_original, [], 'attachment');
    }

    /**
     * Supprime (doucement) un reçu.
     */
    public function destroy(Request $request, Recu $recu): JsonResponse
    {
        $this->authorize('delete', $recu);

        $recu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reçu supprimé avec succès.',
            'data' => null,
        ]);
    }
}
