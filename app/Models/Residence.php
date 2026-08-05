<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Residence extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'syndic_id',
        'name',
        'address',
        'city',
        'postal_code',
        'description',
    ];

    public function syndic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'syndic_id');
    }

    public function immeubles(): HasMany
    {
        return $this->hasMany(Immeuble::class);
    }
}
