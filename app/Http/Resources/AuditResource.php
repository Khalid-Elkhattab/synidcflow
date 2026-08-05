<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reclamation_id' => $this->reclamation_id,
            'charges_snapshot' => $this->charges_snapshot,
            'resultat' => $this->resultat,
            'decision' => $this->decision?->value,
            'statut' => $this->statut->value,
            'modele_ia' => $this->modele_ia,
            'traite_at' => $this->traite_at?->toISOString(),
            'conversation' => $this->whenLoaded('conversation', fn () => [
                'id' => $this->conversation->id,
            ]),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
