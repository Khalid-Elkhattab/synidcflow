<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuditResource;
use App\Jobs\AnalyserReclamationJob;
use App\Models\Audit;
use App\Models\Reclamation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Audits IA')]
class AuditController extends Controller
{
    /**
     * Déclenche l'analyse IA asynchrone d'une réclamation.
     */
    #[Authenticated]
    #[UrlParam('reclamation', 'integer', 'Identifiant de la réclamation à analyser.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Analyse de la réclamation lancée avec succès.',
        'data' => null,
    ], status: 202, description: 'Analyse lancée (traitement asynchrone via la queue).')]
    public function trigger(Reclamation $reclamation): JsonResponse
    {
        $this->authorize('trigger', [Audit::class, $reclamation]);

        AnalyserReclamationJob::dispatch($reclamation);

        return response()->json([
            'success' => true,
            'message' => 'Analyse de la réclamation lancée avec succès.',
            'data' => null,
        ], 202);
    }

    /**
     * Liste des audits visible par le syndic propriétaire ou l'admin.
     */
    #[Authenticated]
    #[Response([
        'success' => true,
        'message' => 'Audits récupérés avec succès.',
        'data' => [
            'audits' => [
                ['id' => 1, 'reclamation_id' => 1, 'charges_snapshot' => ['total' => '120.50', 'statut' => 'paid'], 'resultat' => ['resume' => 'Fuite signalée', 'categorie' => 'plomberie', 'priorite' => 'haute'], 'decision' => 'review', 'statut' => 'completed', 'modele_ia' => 'llama-3.1-8b-instant', 'traite_at' => '2026-08-02T10:00:00.000000Z', 'conversation' => ['id' => 1], 'created_at' => '2026-08-02T10:00:00.000000Z'],
            ],
            'links' => ['first' => '/api/v1/audits?page=1', 'last' => '/api/v1/audits?page=1', 'prev' => null, 'next' => null],
            'meta' => ['current_page' => 1, 'from' => 1, 'last_page' => 1, 'path' => '/api/v1/audits', 'per_page' => 15, 'to' => 1, 'total' => 1],
        ],
    ], description: 'Liste paginée des audits (syndic propriétaire ou admin uniquement).')]
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Audit::class);

        $user = $request->user();

        $audits = Audit::query()
            ->with('conversation')
            ->when(
                $user->role === UserRole::Syndic,
                fn ($query) => $query->whereHas(
                    'reclamation.appartement.immeuble.residence',
                    fn ($query) => $query->where('syndic_id', $user->id)
                )
            )
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Audits récupérés avec succès.',
            'data' => [
                'audits' => AuditResource::collection($audits),
            ],
        ]);
    }

    /**
     * Détail d'un audit (syndic propriétaire ou admin uniquement).
     */
    #[Authenticated]
    #[UrlParam('audit', 'integer', 'Identifiant de l\'audit.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Audit récupéré avec succès.',
        'data' => [
            'audit' => ['id' => 1, 'reclamation_id' => 1, 'charges_snapshot' => ['total' => '120.50', 'statut' => 'paid'], 'resultat' => ['resume' => 'Fuite signalée', 'categorie' => 'plomberie', 'priorite' => 'haute'], 'decision' => 'review', 'statut' => 'completed', 'modele_ia' => 'llama-3.1-8b-instant', 'traite_at' => '2026-08-02T10:00:00.000000Z', 'conversation' => ['id' => 1], 'created_at' => '2026-08-02T10:00:00.000000Z'],
        ],
    ], description: 'Détail d\'un audit, avec sa conversation IA.')]
    public function show(Audit $audit): JsonResponse
    {
        $this->authorize('view', $audit);

        return response()->json([
            'success' => true,
            'message' => 'Audit récupéré avec succès.',
            'data' => [
                'audit' => new AuditResource($audit->load('conversation')),
            ],
        ]);
    }

    /**
     * Liste des audits d'une réclamation précisée (syndic propriétaire
     * ou admin uniquement).
     */
    #[Authenticated]
    #[UrlParam('reclamation', 'integer', 'Identifiant de la réclamation.', example: 1)]
    #[Response([
        'success' => true,
        'message' => 'Audits de la réclamation récupérés avec succès.',
        'data' => [
            'audits' => [
                ['id' => 1, 'reclamation_id' => 1, 'charges_snapshot' => ['total' => '120.50', 'statut' => 'paid'], 'resultat' => ['resume' => 'Fuite signalée', 'categorie' => 'plomberie', 'priorite' => 'haute'], 'decision' => 'review', 'statut' => 'completed', 'modele_ia' => 'llama-3.1-8b-instant', 'traite_at' => '2026-08-02T10:00:00.000000Z', 'conversation' => ['id' => 1], 'created_at' => '2026-08-02T10:00:00.000000Z'],
            ],
        ],
    ], description: 'Liste paginée des audits d\'une réclamation (une réclamation peut être analysée plusieurs fois).')]
    public function forReclamation(Reclamation $reclamation): JsonResponse
    {
        $this->authorize('viewForReclamation', [Audit::class, $reclamation]);

        $audits = $reclamation->audits()
            ->with('conversation')
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Audits de la réclamation récupérés avec succès.',
            'data' => [
                'audits' => AuditResource::collection($audits),
            ],
        ]);
    }
}
