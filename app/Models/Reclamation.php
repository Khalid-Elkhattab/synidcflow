<?php

namespace App\Models;

use App\Enums\ReclamationPriorite;
use App\Enums\ReclamationStatut;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reclamation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'resident_id',
        'appartement_id',
        'titre',
        'description',
        'statut',
        'priorite',
    ];

    protected function casts(): array
    {
        return [
            'statut' => ReclamationStatut::class,
            'priorite' => ReclamationPriorite::class,
        ];
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function appartement(): BelongsTo
    {
        return $this->belongsTo(Appartement::class);
    }

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }
}
