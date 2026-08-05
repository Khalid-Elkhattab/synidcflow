<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MarkChargePaidRequest extends FormRequest
{
    /**
     * Le paiement est déclaré par le syndic propriétaire ou l'admin.
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
            'date_paiement' => ['nullable', 'date'],
        ];
    }
}
