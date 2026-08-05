<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Charge;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreChargeRequest extends FormRequest
{
    /**
     * Création autorisée pour un appartement accessible.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', [Charge::class, $this->route('appartement')]) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'montant' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'date_echeance' => ['required', 'date'],
            'periode' => ['nullable', 'string', 'max:255'],
        ];
    }
}
