<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Ai\Models\Conversation as LaravelConversation;

class Conversation extends LaravelConversation
{
    /**
     * L'audit documenté par cette conversation (relation 1--1).
     */
    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }
}
