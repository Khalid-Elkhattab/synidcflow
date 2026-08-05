<?php

namespace App\Services;

use App\Agents\AnalyseReclamationAgent;
use App\Enums\AuditDecision;
use App\Enums\AuditStatut;
use App\Models\Audit;
use App\Models\Conversation;
use App\Models\Reclamation;
use Illuminate\Support\Facades\DB;
use Throwable;

class AnalyseReclamationService
{
    /**
     * Analyse une réclamation : fige le snapshot des charges de
     * l'appartement, invoque l'agent IA et enregistre l'audit.
     */
    public function analyser(Reclamation $reclamation): Audit
    {
        return DB::transaction(function () use ($reclamation): Audit {
            $charges = $reclamation->appartement->charges;

            $audit = Audit::create([
                'reclamation_id' => $reclamation->id,
                'charges_snapshot' => $charges->map(fn ($charge) => [
                    'id' => $charge->id,
                    'libelle' => $charge->libelle,
                    'description' => $charge->description,
                    'montant' => $charge->montant,
                    'date_echeance' => $charge->date_echeance?->toDateString(),
                    'statut' => $charge->statut->value,
                    'periode' => $charge->periode,
                    'date_paiement' => $charge->date_paiement?->toDateString(),
                ])->values()->all(),
                'statut' => AuditStatut::Processing,
            ]);

            try {
                $resultat = $this->invoquerAgent($reclamation, $audit);

                $audit->update([
                    'decision' => AuditDecision::from($resultat['decision']),
                    'resultat' => $resultat['resultat'],
                    'statut' => AuditStatut::Completed,
                    'modele_ia' => $resultat['modele_ia'],
                    'traite_at' => now(),
                ]);

                if ($resultat['conversation_id'] !== null) {
                    Conversation::query()
                        ->whereKey($resultat['conversation_id'])
                        ->update(['audit_id' => $audit->id]);
                }

                return $audit;
            } catch (Throwable) {
                $audit->update([
                    'statut' => AuditStatut::Failed,
                    'traite_at' => now(),
                ]);

                return $audit;
            }
        });
    }

    /**
     * Invocation de l'agent IA avec le snapshot figé.
     *
     * @return array{decision: string, resultat: array, modele_ia: string, conversation_id: ?string}
     */
    protected function invoquerAgent(Reclamation $reclamation, Audit $audit): array
    {
        $prompt = sprintf(
            "Réclamation n°%d : %s\n\n%s\n\nSnapshot des charges de l'appartement :\n%s",
            $reclamation->id,
            $reclamation->titre,
            $reclamation->description,
            json_encode($audit->charges_snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
        );

        $response = AnalyseReclamationAgent::make()
            ->forAudit($audit)
            ->prompt($prompt);

        return [
            'decision' => $response['decision'],
            'resultat' => $response['resultat'],
            'modele_ia' => $response->meta?->model ?? 'groq',
            'conversation_id' => $response->conversationId,
        ];
    }
}
