<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Appartement;
use App\Models\Reclamation;
use App\Models\User;

class ReclamationPolicy
{
    /**
     * Les trois rôles accèdent à la liste ; filtrage réel dans index().
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Résident : uniquement les siennes. Syndic : celles de ses résidences.
     * Admin : toutes.
     */
    public function view(User $user, Reclamation $reclamation): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        if ($user->role === UserRole::Resident) {
            return $reclamation->resident_id === $user->id;
        }

        return $user->id === $reclamation->appartement?->immeuble?->residence?->syndic_id;
    }

    /**
     * Un résident dépose une réclamation pour un appartement qui lui est
     * affecté (vérifié ici ET dans StoreReclamationRequest).
     */
    public function create(User $user, ?Appartement $appartement = null): bool
    {
        return $user->role === UserRole::Resident
            && $appartement !== null
            && $appartement->resident_id === $user->id;
    }

    /**
     * Traitement : syndic (appartements de ses résidences) ou admin.
     */
    public function update(User $user, Reclamation $reclamation): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $reclamation->appartement?->immeuble?->residence?->syndic_id;
    }

    public function delete(User $user, Reclamation $reclamation): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function restore(User $user, Reclamation $reclamation): bool
    {
        return false;
    }

    public function forceDelete(User $user, Reclamation $reclamation): bool
    {
        return false;
    }
}
