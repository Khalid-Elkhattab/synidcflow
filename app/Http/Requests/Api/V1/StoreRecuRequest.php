<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ChargeStatut;
use App\Models\Charge;
use App\Models\Recu;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRecuRequest extends FormRequest
{
    /**
     * Upload réservé au syndic propriétaire ou à l'admin.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', [Recu::class, $this->route('charge')]) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fichier' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:10240'],
            'date_paiement' => ['required', 'date'],
            'montant_paye' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    /**
     * Règles custom (doc §14.2) : charge payée et pas de reçu actif.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $charge = $this->route('charge');

            if ($charge->statut !== ChargeStatut::Paid) {
                $validator->errors()->add('fichier', "Un reçu ne peut être ajouté qu'à une charge payée.");
            }

            if ($charge->recu()->exists()) {
                $validator->errors()->add('fichier', 'Cette charge possède déjà un reçu.');
            }
        });
    }
}
