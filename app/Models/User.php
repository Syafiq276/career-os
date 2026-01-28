<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'skills',
        'github_username',
        'github_token',
        'linkedin_url',
        'portfolio_url',
        'is_profile_public',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_profile_public' => 'boolean',
            'skills' => 'array',
        ];
    }

    /**
     * Get all applications for this user.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get all projects (quests) for this user.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get featured projects only.
     */
    public function featuredProjects()
    {
        return $this->hasMany(Project::class)->where('is_featured', true)->orderBy('xp_gained', 'desc');
    }

    /**
     * Get all skills for this user.
     */
    public function skills_data()
    {
        return $this->hasMany(Skill::class);
    }

    /**
     * Scope query for public profiles only.
     */
    public function scopePublic($query)
    {
        return $query->where('is_profile_public', true);
    }

    /**
     * Calculate user's total XP from completed projects.
     */
    public function getTotalXpAttribute(): int
    {
        return $this->projects()->sum('xp_gained');
    }

    /**
     * Calculate user's level based on total XP.
     * Formula: Level = Floor(Total XP / threshold)
     * Threshold configurable via config/careeros.php
     */
    public function getLevelAttribute(): int
    {
        $threshold = config('careeros.xp.level_threshold', 1000);
        $maxLevel = config('careeros.xp.max_level', 100);
        
        $calculatedLevel = (int) floor($this->total_xp / $threshold);
        
        return min($calculatedLevel, $maxLevel);
    }

    /**
     * Get XP progress to next level.
     */
    public function getXpToNextLevelAttribute(): int
    {
        $threshold = config('careeros.xp.level_threshold', 1000);
        $nextLevelXp = ($this->level + 1) * $threshold;
        return $nextLevelXp - $this->total_xp;
    }
}
