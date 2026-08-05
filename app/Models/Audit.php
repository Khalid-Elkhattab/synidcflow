<?php

namespace App\Models;

use App\Enums\AuditDecision;
use App\Enums\AuditStatut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reclamation_id',
        'charges_snapshot',
        'resultat',
        'decision',
        'statut',
        'modele_ia',
        'traite_at',
    ];

    protected function casts(): array
    {
        return [
            'charges_snapshot' => 'array',
            'resultat' => 'array',
            'decision' => AuditDecision::class,
            'statut' => AuditStatut::class,
            'traite_at' => 'datetime',
        ];
    }

    public function reclamation(): BelongsTo
    {
        return $this->belongsTo(Reclamation::class);
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class);
    }
}
