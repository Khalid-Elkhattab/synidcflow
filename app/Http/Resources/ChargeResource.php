<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChargeResource extends JsonResource
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
            'appartement_id' => $this->appartement_id,
            'libelle' => $this->libelle,
            'description' => $this->description,
            'montant' => $this->montant,
            'date_echeance' => $this->date_echeance,
            'statut' => $this->statut,
            'periode' => $this->periode,
            'date_paiement' => $this->date_paiement,
            'recu' => new RecuResource($this->whenLoaded('recu')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
