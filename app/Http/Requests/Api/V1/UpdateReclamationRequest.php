<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ReclamationStatut;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReclamationRequest extends FormRequest
{
    /**
     * Traitement réservé au syndic propriétaire ou à l'admin.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('reclamation')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'statut' => ['required', Rule::enum(ReclamationStatut::class)],
        ];
    }
}
