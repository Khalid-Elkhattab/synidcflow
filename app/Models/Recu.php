<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'charge_id',
        'fichier',
        'nom_original',
        'type_mime',
        'taille',
        'date_paiement',
        'montant_paye',
    ];

    protected function casts(): array
    {
        return [
            'taille' => 'integer',
            'date_paiement' => 'date',
            'montant_paye' => 'decimal:2',
        ];
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class);
    }
}
