<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChargeRequest extends FormRequest
{
    /**
     * Modification autorisée pour le propriétaire de l'appartement.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('charge')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'libelle' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'montant' => ['sometimes', 'required', 'numeric', 'min:0', 'max:99999999.99'],
            'date_echeance' => ['sometimes', 'required', 'date'],
            'periode' => ['nullable', 'string', 'max:255'],
        ];
    }
}
