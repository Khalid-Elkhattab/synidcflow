<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Immeuble extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'residence_id',
        'name',
        'address',
        'nombre_etages',
    ];

    protected function casts(): array
    {
        return [
            'nombre_etages' => 'integer',
        ];
    }

    public function residence(): BelongsTo
    {
        return $this->belongsTo(Residence::class);
    }

    public function appartements(): HasMany
    {
        return $this->hasMany(Appartement::class);
    }
}
