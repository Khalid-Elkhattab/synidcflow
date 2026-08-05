<?php

namespace App\Models;

use App\Enums\ChargeStatut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Charge extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'appartement_id',
        'libelle',
        'description',
        'montant',
        'date_echeance',
        'statut',
        'periode',
        'date_paiement',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'decimal:2',
            'date_echeance' => 'date',
            'date_paiement' => 'date',
            'statut' => ChargeStatut::class,
        ];
    }

    public function appartement(): BelongsTo
    {
        return $this->belongsTo(Appartement::class);
    }

    public function recu(): HasOne
    {
        return $this->hasOne(Recu::class);
    }
}
