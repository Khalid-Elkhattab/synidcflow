<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Appartement;
use App\Models\Immeuble;
use App\Models\User;

class AppartementPolicy
{
    /**
     * L'index est autorisé pour les trois rôles ; filtrage réel dans index().
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Admin : toujours. Syndic : propriétaire de la résidence parente.
     * Résident : uniquement l'appartement qui lui est affecté.
     */
    public function view(User $user, Appartement $appartement): bool
    {
        if ($this->manage($user, $appartement)) {
            return true;
        }

        return $user->role === UserRole::Resident
            && $user->id === $appartement->resident_id;
    }

    /**
     * Création dans un immeuble accessible (admin ou syndic propriétaire).
     */
    public function create(User $user, Immeuble $immeuble): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $immeuble->residence?->syndic_id;
    }

    public function update(User $user, Appartement $appartement): bool
    {
        return $this->manage($user, $appartement);
    }

    /**
     * Affecter/désaffecter un résident : admin ou syndic propriétaire.
     */
    public function assign(User $user, Appartement $appartement): bool
    {
        return $this->manage($user, $appartement);
    }

    public function delete(User $user, Appartement $appartement): bool
    {
        return $this->manage($user, $appartement);
    }

    public function restore(User $user, Appartement $appartement): bool
    {
        return false;
    }

    public function forceDelete(User $user, Appartement $appartement): bool
    {
        return false;
    }

    /**
     * Admin : toujours. Syndic : propriétaire de la résidence parente.
     */
    private function manage(User $user, Appartement $appartement): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $appartement->immeuble?->residence?->syndic_id;
    }
}
