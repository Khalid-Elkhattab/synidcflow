<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAppartementRequest extends FormRequest
{
    /**
     * Modification autorisée pour le propriétaire de la résidence parente.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('appartement')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numero' => ['sometimes', 'required', 'string', 'max:50'],
            'etage' => ['nullable', 'integer', 'min:0', 'max:255'],
            'superficie' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }
}
