<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Audit;
use App\Models\Reclamation;
use App\Models\User;

class AuditPolicy
{
    /**
     * Le résident ne voit jamais un AUDIT (doc §18).
     */
    public function viewAny(User $user): bool
    {
        return $user->role !== UserRole::Resident;
    }

    /**
     * Syndic propriétaire de la résidence ou admin uniquement.
     */
    public function view(User $user, Audit $audit): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $audit->reclamation?->appartement?->immeuble?->residence?->syndic_id;
    }

    /**
     * Déclenchement de l'analyse IA : syndic (réclamations de ses
     * résidences) ou admin. Jamais le résident.
     */
    public function trigger(User $user, ?Reclamation $reclamation = null): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $reclamation !== null
            && $user->id === $reclamation->appartement?->immeuble?->residence?->syndic_id;
    }

    /**
     * Consultation des audits d'une réclamation : syndic propriétaire ou
     * admin uniquement. Le résident n'y accède jamais (doc §18).
     */
    public function viewForReclamation(User $user, Reclamation $reclamation): bool
    {
        if ($user->role === UserRole::Admin) {
            return true;
        }

        return $user->role === UserRole::Syndic
            && $user->id === $reclamation->appartement?->immeuble?->residence?->syndic_id;
    }
}
