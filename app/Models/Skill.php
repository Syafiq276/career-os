<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'score',
        'category',
    ];

    protected $casts = [
        'score' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getScoreColorAttribute(): string
    {
        return match(true) {
            $this->score >= 80 => 'text-emerald-400',
            $this->score >= 60 => 'text-blue-400',
            $this->score >= 40 => 'text-yellow-400',
            default => 'text-red-400',
        };
    }
}
