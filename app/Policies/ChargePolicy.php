<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Appartement;
use App\Models\Charge;
use App\Models\User;

class ChargePolicy
{
    /**
     * Les trois rôles accèdent à la liste ; filtrage réel dans index().
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Admin : toujours. Syndic : propriétaire de l'appartement.
     * Résident : uniquement les charges de l'appartement qui lui est affecté.
     */
    public function view(User $user, Charge $charge): bool
    {
        if ($this->manage($user, $charge)) {
            return true;
        }

        return $user->role === UserRole::Resident
            && $user->id === $charge->appartement?->resident_id;
    }

    /**
     * Création pour un appartement accessible (admin ou syndic propriétaire).
     */
    public function create(User $user, Appartement $appartement): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $appartement->immeuble?->residence?->syndic_id;
    }

    public function update(User $user, Charge $charge): bool
    {
        return $this->manage($user, $charge);
    }

    public function delete(User $user, Charge $charge): bool
    {
        return $this->manage($user, $charge);
    }

    public function restore(User $user, Charge $charge): bool
    {
        return false;
    }

    public function forceDelete(User $user, Charge $charge): bool
    {
        return false;
    }

    /**
     * Admin : toujours. Syndic : propriétaire de la résidence parente.
     */
    private function manage(User $user, Charge $charge): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $charge->appartement?->immeuble?->residence?->syndic_id;
    }
}
