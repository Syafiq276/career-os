<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'difficulty',
        'tech_stack',
        'repo_link',
        'is_featured',
        'xp_gained',
    ];

    protected $casts = [
        'tech_stack' => 'array',
        'is_featured' => 'boolean',
        'xp_gained' => 'integer',
    ];

    public const DIFFICULTIES = [
        'Tutorial',
        'Normal',
        'Hardcore',
        'Legendary',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty) {
            'Tutorial' => 'text-green-400',
            'Normal' => 'text-blue-400',
            'Hardcore' => 'text-purple-400',
            'Legendary' => 'text-yellow-400',
            default => 'text-gray-400',
        };
    }
}
