<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateImmeubleRequest extends FormRequest
{
    /**
     * Modification autorisée pour le propriétaire de la résidence parente.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('immeuble')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'nombre_etages' => ['nullable', 'integer', 'min:0', 'max:255'],
        ];
    }
}
