<?php

namespace App\Agents;

use App\Enums\AuditDecision;
use App\Models\Audit;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Promptable;
use Stringable;

class AnalyseReclamationAgent implements Agent, Conversational, HasStructuredOutput
{
    use Promptable;
    use RemembersConversations;

    /**
     * Instruction système de l'agent d'analyse.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Tu es un expert en gestion de copropriété. Tu analyses des réclamations de
résidents en t'appuyant sur l'état des charges de l'appartement concerné.

Règles :
- Décision `accepted` : la réclamation est fondée (charge payée à tort,
  montant erroné, double facturation, service non rendu...).
- Décision `rejected` : la réclamation est manifestement infondée.
- Décision `review` : des éléments manquent ou la situation est ambiguë,
  une instruction manuelle par le syndic est nécessaire.
- `resultat` doit contenir `justification` (texte bref et factuel) et
  `points` (liste des points factuels constatés).
- Le JSON de sortie doit être conforme au schéma fourni, sans texte
  additionnel.
PROMPT;
    }

    /**
     * Démarre une conversation rattachée à l'audit analysé.
     */
    public function forAudit(Audit $audit): static
    {
        return $this->forParticipant($audit);
    }

    /**
     * Schéma de sortie structurée de l'agent.
     *
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'decision' => $schema->string()
                ->enum([
                    AuditDecision::Accepted->value,
                    AuditDecision::Rejected->value,
                    AuditDecision::Review->value,
                ])
                ->required(),
            'resultat' => $schema->object([
                'justification' => $schema->string()->required(),
                'points' => $schema->array()->required(),
            ])->required(),
        ];
    }
}
