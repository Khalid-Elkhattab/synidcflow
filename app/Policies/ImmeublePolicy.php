<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Immeuble;
use App\Models\Residence;
use App\Models\User;

class ImmeublePolicy
{
    /**
     * Les trois rôles accèdent à la liste ; filtrage réel dans index().
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Admin : toujours. Syndic : propriétaire de la résidence parente.
     * Résident : uniquement s'il possède un appartement dans cet immeuble.
     */
    public function view(User $user, Immeuble $immeuble): bool
    {
        if ($this->manage($user, $immeuble)) {
            return true;
        }

        return $user->role === UserRole::Resident
            && $immeuble->appartements()
                ->where('resident_id', $user->id)
                ->exists();
    }

    /**
     * Création dans une résidence accessible (admin ou syndic propriétaire).
     */
    public function create(User $user, Residence $residence): bool
    {
        return $user->role === UserRole::Admin
            || ($user->role === UserRole::Syndic
                && $user->id === $residence->syndic_id);
    }

    public function update(User $user, Immeuble $immeuble): bool
    {
        return $this->manage($user, $immeuble);
    }

    public function delete(User $user, Immeuble $immeuble): bool
    {
        return $this->manage($user, $immeuble);
    }

    public function restore(User $user, Immeuble $immeuble): bool
    {
        return false;
    }

    public function forceDelete(User $user, Immeuble $immeuble): bool
    {
        return false;
    }

    /**
     * Admin : toujours. Syndic : propriétaire de la résidence parente.
     */
    private function manage(User $user, Immeuble $immeuble): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $immeuble->residence?->syndic_id;
    }
}
