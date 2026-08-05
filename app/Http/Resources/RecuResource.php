<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecuResource extends JsonResource
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
            'charge_id' => $this->charge_id,
            'nom_original' => $this->nom_original,
            'type_mime' => $this->type_mime,
            'taille' => $this->taille,
            'date_paiement' => $this->date_paiement,
            'montant_paye' => $this->montant_paye,
            'download_url' => route('recus.download', ['recu' => $this->id]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
