<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Residence extends Model
{
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
        return $this->BelongsTo(User::class, 'syndic_id');
    }
}
