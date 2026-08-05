<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Charge;
use App\Models\Recu;
use App\Models\User;

class RecuPolicy
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
     * Résident : uniquement les reçus des charges de l'appartement affecté.
     */
    public function view(User $user, Recu $recu): bool
    {
        if ($this->manage($user, $recu)) {
            return true;
        }

        return $user->role === UserRole::Resident
            && $user->id === $recu->charge?->appartement?->resident_id;
    }

    /**
     * Upload sur une charge accessible (admin ou syndic propriétaire).
     */
    public function create(User $user, Charge $charge): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $charge->appartement?->immeuble?->residence?->syndic_id;
    }

    public function delete(User $user, Recu $recu): bool
    {
        return $this->manage($user, $recu);
    }

    public function restore(User $user, Recu $recu): bool
    {
        return false;
    }

    public function forceDelete(User $user, Recu $recu): bool
    {
        return false;
    }

    /**
     * Admin : toujours. Syndic : propriétaire de la résidence parente.
     */
    private function manage(User $user, Recu $recu): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $recu->charge?->appartement?->immeuble?->residence?->syndic_id;
    }
}
