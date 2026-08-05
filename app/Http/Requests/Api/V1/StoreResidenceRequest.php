<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\UserRole;
use App\Models\Residence;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreResidenceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create', Residence::class) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
        ];

        if ($this->user()?->role === UserRole::Admin) {
            $rules['syndic_id'] = [
                'required',
                'exists:users,id',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $isSyndic = User::query()
                        ->whereKey($value)
                        ->where('role', UserRole::Syndic->value)
                        ->exists();

                    if (! $isSyndic) {
                        $fail("L'utilisateur sélectionné doit avoir le rôle syndic.");
                    }
                },
            ];
        }

        return $rules;
    }
}
