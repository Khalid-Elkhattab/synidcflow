<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\UserRole;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AssignResidentRequest extends FormRequest
{
    /**
     * Affectation réservée au syndic propriétaire ou à l'admin.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('assign', $this->route('appartement')) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'resident_id' => [
                'required',
                'exists:users,id',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $isResident = User::query()
                        ->whereKey($value)
                        ->where('role', UserRole::Resident->value)
                        ->exists();

                    if (! $isResident) {
                        $fail("L'utilisateur sélectionné doit avoir le rôle résident.");
                    }
                },
            ],
        ];
    }
}
