<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * Using $fillable instead of $guarded for explicit control over what can be mass-assigned.
     * This is a security best practice to prevent mass-assignment vulnerabilities.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'company_name',
        'job_title',
        'job_link',
        'location',
        'salary_range',
        'status',
        'applied_at',
        'interview_at',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     * 
     * Casting dates to 'date' ensures Carbon instances are automatically returned,
     * making date manipulation easy (e.g., $application->applied_at->format('Y-m-d')).
     * Using 'date' instead of 'datetime' since we don't need time precision for this use case.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'applied_at' => 'date',
        'interview_at' => 'date',
    ];

    /**
     * The possible status values.
     * 
     * Defining as a constant makes it reusable in validation rules and UI dropdowns.
     */
    public const STATUS_APPLIED = 'applied';
    public const STATUS_SCREENING = 'screening';
    public const STATUS_INTERVIEW = 'interview';
    public const STATUS_OFFER = 'offer';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_GHOSTED = 'ghosted';

    /**
     * Get all available statuses.
     *
     * @return array<int, string>
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_APPLIED,
            self::STATUS_SCREENING,
            self::STATUS_INTERVIEW,
            self::STATUS_OFFER,
            self::STATUS_REJECTED,
            self::STATUS_GHOSTED,
        ];
    }

    /**
     * Get the user that owns the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope query for specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope query for active applications (not rejected/ghosted).
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_APPLIED, self::STATUS_SCREENING, self::STATUS_INTERVIEW, self::STATUS_OFFER]);
    }

    /**
     * Scope query to search by company or job title.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('company_name', 'like', "%{$search}%")
              ->orWhere('job_title', 'like', "%{$search}%");
        });
    }

    /**
     * Scope query for recent applications.
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('applied_at', 'desc');
    }
}

