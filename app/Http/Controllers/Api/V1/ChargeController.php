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
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Charges')]
class ChargeController extends Controller
{
    /**
     * Liste des charges d'un appartement accessible.
     */
    #[Authenticated]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Charges récupérées avec succès.',
        'data' => [
            'charges' => [
                ['id' => 1, 'appartement_id' => 1, 'libelle' => 'Charge de copropriété', 'description' => null, 'montant' => '150.50', 'date_echeance' => '2026-08-31', 'statut' => 'pending', 'periode' => 'Août 2026', 'date_paiement' => null,
                    'recu' => null, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
            ],
            'links' => ['first' => '/api/v1/appartements/1/charges?page=1', 'last' => '/api/v1/appartements/1/charges?page=1', 'prev' => null, 'next' => null],
            'meta' => ['current_page' => 1, 'from' => 1, 'last_page' => 1, 'path' => '/api/v1/appartements/1/charges', 'per_page' => 15, 'to' => 1, 'total' => 1],
        ],
    ], description: 'Liste paginée des charges d\'un appartement.')]
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
    #[Authenticated]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[BodyParam('libelle', 'string', 'Libellé de la charge.', example: 'Charge de copropriété')]
    #[BodyParam('description', 'string', 'Description.', required: false, example: 'Charges communes du mois')]
    #[BodyParam('montant', 'number', 'Montant de la charge (>= 0).', example: 120.5)]
    #[BodyParam('date_echeance', 'string', 'Date d\'échéance (format date).', example: '2026-08-31')]
    #[BodyParam('periode', 'string', 'Période concernée.', required: false, example: 'Août 2026')]
    #[Response([
        'success' => true,
        'message' => 'Charge créée avec succès.',
        'data' => [
            'charge' => ['id' => 1, 'appartement_id' => 1, 'libelle' => 'Charge de copropriété', 'description' => null, 'montant' => '120.50', 'date_echeance' => '2026-08-31', 'statut' => 'pending', 'periode' => 'Août 2026', 'date_paiement' => null,
                'recu' => null, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], status: 201, description: 'Charge créée avec le statut `pending`.')]
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
    #[Authenticated]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[UrlParam('charge', 'integer', 'Identifiant de la charge.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Charge récupérée avec succès.',
        'data' => [
            'charge' => ['id' => 1, 'appartement_id' => 1, 'libelle' => 'Charge de copropriété', 'description' => null, 'montant' => '120.50', 'date_echeance' => '2026-08-31', 'statut' => 'paid', 'periode' => 'Août 2026', 'date_paiement' => '2026-08-15',
                'recu' => ['id' => 1, 'charge_id' => 1, 'nom_original' => 'recu.jpg', 'type_mime' => 'image/jpeg', 'taille' => 102400, 'date_paiement' => '2026-08-15', 'montant_paye' => '120.50', 'download_url' => '/api/v1/recus/1/download', 'created_at' => '2026-08-15T10:00:00.000000Z', 'updated_at' => '2026-08-15T10:00:00.000000Z'],
                'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-15T10:00:00.000000Z'],
        ],
    ], description: 'Détail d\'une charge, avec son reçu s\'il a été téléversé.')]
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
    #[Authenticated]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[UrlParam('charge', 'integer', 'Identifiant de la charge.', example: 1)]
    #[BodyParam('libelle', 'string', 'Libellé de la charge.', required: false, example: 'Charge de copropriété')]
    #[BodyParam('description', 'string', 'Description.', required: false, example: 'Chargement annuel du mois')]
    #[BodyParam('montant', 'number', 'Montant de la charge.', required: false, example: 150)]
    #[BodyParam('date_echeance', 'string', 'Date d\'échéance.', required: false, example: '2026-09-30')]
    #[BodyParam('periode', 'string', 'Période concernée.', required: false, example: 'Septembre 2026')]
    #[Response([
        'success' => true,
        'message' => 'Charge mise à jour avec succès.',
        'data' => [
            'charge' => ['id' => 1, 'appartement_id' => 1, 'libelle' => 'Charge de copropriété', 'description' => null, 'montant' => '150.00', 'date_echeance' => '2026-09-30', 'statut' => 'pending', 'periode' => 'Septembre 2026', 'date_paiement' => null,
                'recu' => null, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-01T10:00:00.000000Z'],
        ],
    ], description: 'Charge mise à jour (le statut n\'est pas modifiable ici).')]
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
    #[Authenticated]
    #[UrlParam('appartement', 'integer', 'Identifiant de l\'appartement.', example: 1)]
    #[UrlParam('charge', 'integer', 'Identifiant de la charge.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Charge supprimée avec succès.',
        'data' => null,
    ], description: 'Charge supprimée (soft delete).')]
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
     * Déclare manuellement le paiement : pending → paid (doc §10).
     */
    #[Authenticated]
    #[UrlParam('charge', 'integer', 'Identifiant de la charge.', example: 1)]
    #[BodyParam('date_paiement', 'string', 'Date du paiement. Par défaut : aujourd\'hui.', required: false, example: '2026-08-15')]
    #[Response([
        'success' => true,
        'message' => 'Charge marquée comme payée avec succès.',
        'data' => [
            'charge' => ['id' => 1, 'appartement_id' => 1, 'libelle' => 'Charge de copropriété', 'description' => null, 'montant' => '120.50', 'date_echeance' => '2026-08-31', 'statut' => 'paid', 'periode' => 'Août 2026', 'date_paiement' => '2026-08-15',
                'recu' => null, 'created_at' => '2026-08-01T10:00:00.000000Z', 'updated_at' => '2026-08-15T10:00:00.000000Z'],
        ],
    ], description: 'Charge marquée payée.')]
    #[Response([
        'success' => false,
        'message' => 'Cette charge est déjà marquée comme payée.',
        'data' => null,
    ], status: 409, description: 'La charge est déjà payée.')]
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
