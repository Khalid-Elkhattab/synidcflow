<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Appartement;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppartementRequest extends FormRequest
{
    /**
     * Création autorisée dans un immeuble accessible.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', [Appartement::class, $this->route('immeuble')]) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numero' => ['required', 'string', 'max:50'],
            'etage' => ['nullable', 'integer', 'min:0', 'max:255'],
            'superficie' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }
}
