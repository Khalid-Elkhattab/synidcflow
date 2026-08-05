<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appartement extends Model
{
    use SoftDeletes;

    /**
     * Statut calculé, jamais stocké en base (doc §6).
     */
    protected $appends = ['statut'];

    protected $fillable = [
        'immeuble_id',
        'resident_id',
        'numero',
        'etage',
        'superficie',
    ];

    protected function casts(): array
    {
        return [
            'etage' => 'integer',
            'superficie' => 'decimal:2',
        ];
    }

    public function immeuble(): BelongsTo
    {
        return $this->belongsTo(Immeuble::class);
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function charges(): HasMany
    {
        return $this->hasMany(Charge::class);
    }

    public function reclamations(): HasMany
    {
        return $this->hasMany(Reclamation::class);
    }

    public function getStatutAttribute(): string
    {
        return $this->resident_id === null ? 'vacant' : 'occupied';
    }
}
