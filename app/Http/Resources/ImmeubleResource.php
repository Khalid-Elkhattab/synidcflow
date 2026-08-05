<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImmeubleResource extends JsonResource
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
            'residence_id' => $this->residence_id,
            'name' => $this->name,
            'address' => $this->address,
            'nombre_etages' => $this->nombre_etages,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
