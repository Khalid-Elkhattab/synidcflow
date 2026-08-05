<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Residence;
use App\Models\User;

class ResidencePolicy
{
    /**
     * Autorise l'accès à la liste pour les trois rôles.
     * Le filtrage réel s'effectue dans ResidenceController@index.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Admin : toujours. Syndic : propriétaire uniquement.
     * Résident : uniquement via une affectation indirecte
     * (USER → APPARTEMENT → IMMEUBLE → RESIDENCE).
     */
    public function view(User $user, Residence $residence): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        if ($user->role === UserRole::Syndic) {
            return $user->id === $residence->syndic_id;
        }

        return $residence->immeubles()
            ->whereHas('appartements', fn ($query) => $query->where('resident_id', $user->id))
            ->exists();
    }

    /**
     * Admin (avec syndic_id fourni) ou syndic pour lui-même. Résident : jamais.
     */
    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin
            || $user->role === UserRole::Syndic;
    }

    /**
     * Admin : toujours. Syndic : propriétaire uniquement.
     */
    public function update(User $user, Residence $residence): bool
    {
        return $this->view($user, $residence);
    }

    /**
     * Admin : toujours. Syndic : propriétaire uniquement.
     */
    public function delete(User $user, Residence $residence): bool
    {
        return $this->view($user, $residence);
    }

    public function restore(User $user, Residence $residence): bool
    {
        return false;
    }

    public function forceDelete(User $user, Residence $residence): bool
    {
        return false;
    }
}
