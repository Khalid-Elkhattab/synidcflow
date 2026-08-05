<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Immeuble;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreImmeubleRequest extends FormRequest
{
    /**
     * Création autorisée dans une résidence accessible.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', [Immeuble::class, $this->route('residence')]) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'nombre_etages' => ['nullable', 'integer', 'min:0', 'max:255'],
        ];
    }
}
