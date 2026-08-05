<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\ReclamationPriorite;
use App\Models\Appartement;
use App\Models\Reclamation;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReclamationRequest extends FormRequest
{
    /**
     * Vérifie que l'appartement ciblé est affecté à l'utilisateur (doc §14.2).
     */
    public function authorize(): bool
    {
        $appartement = Appartement::find($this->input('appartement_id'));

        return $this->user()?->can('create', [Reclamation::class, $appartement]) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appartement_id' => ['required', 'exists:appartements,id'],
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priorite' => ['nullable', Rule::enum(ReclamationPriorite::class)],
        ];
    }
}
