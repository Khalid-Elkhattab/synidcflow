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
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Group('Reçus')]
class RecuController extends Controller
{
    /**
     * Téléverse un reçu scanné (JPG/PNG) pour une charge payée.
     */
    #[Authenticated]
    #[UrlParam('charge', 'integer', 'Identifiant de la charge (doit être payée).', example: 1)]
    #[BodyParam('fichier', 'file', 'Fichier image du reçu (JPG, JPEG ou PNG, max 10 Mo).')]
    #[BodyParam('date_paiement', 'string', 'Date du paiement.', example: '2026-08-15')]
    #[BodyParam('montant_paye', 'number', 'Montant payé.', example: 120.5)]
    #[Response([
        'success' => true,
        'message' => 'Reçu téléversé avec succès.',
        'data' => [
            'recu' => ['id' => 1, 'charge_id' => 1, 'nom_original' => 'recu.jpg', 'type_mime' => 'image/jpeg', 'taille' => 102400, 'date_paiement' => '2026-08-15', 'montant_paye' => '120.50', 'download_url' => '/api/v1/recus/1/download', 'created_at' => '2026-08-15T10:00:00.000000Z', 'updated_at' => '2026-08-15T10:00:00.000000Z'],
        ],
    ], status: 201, description: 'Reçu téléversé. Un seul reçu actif par charge.')]
    #[Response([
        'success' => false,
        'message' => 'Un reçu ne peut être ajouté qu\'à une charge payée.',
        'errors' => ['fichier' => ['Un reçu ne peut être ajouté qu\'à une charge payée.']],
    ], status: 422, description: 'La charge n\'est pas payée ou possède déjà un reçu.')]
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
    #[Authenticated]
    #[UrlParam('recu', 'integer', 'Identifiant du reçu.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Reçu récupéré avec succès.',
        'data' => [
            'recu' => ['id' => 1, 'charge_id' => 1, 'nom_original' => 'recu.jpg', 'type_mime' => 'image/jpeg', 'taille' => 102400, 'date_paiement' => '2026-08-15', 'montant_paye' => '120.50', 'download_url' => '/api/v1/recus/1/download', 'created_at' => '2026-08-15T10:00:00.000000Z', 'updated_at' => '2026-08-15T10:00:00.000000Z'],
        ],
    ], description: 'Métadonnées du reçu (le fichier se télécharge via `download_url`).')]
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
    #[Authenticated]
    #[UrlParam('recu', 'integer', 'Identifiant du reçu.', example: 1)]
    #[Response('', status: 200, description: 'Retourne le fichier du reçu en pièce jointe (Content-Disposition: attachment).')]
    #[Response([
        'success' => false,
        'message' => 'Le fichier du reçu est introuvable.',
        'data' => null,
    ], status: 404, description: 'Fichier absent du stockage.')]
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
    #[Authenticated]
    #[UrlParam('recu', 'integer', 'Identifiant du reçu.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Reçu supprimé avec succès.',
        'data' => null,
    ], description: 'Reçu supprimé (soft delete).')]
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
