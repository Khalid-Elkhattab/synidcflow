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

class AuditController extends Controller
{
    /**
     * Déclenche l'analyse IA asynchrone d'une réclamation.
     */
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
