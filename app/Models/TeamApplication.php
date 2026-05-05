<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'position',
        'resume_path',
        'resume_original_name',
        'status',
    ];

    /**
     * Get the user who submitted this application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the applicant's name from the related user.
     */
    public function getApplicantNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    /**
     * Get the applicant's email from the related user.
     */
    public function getApplicantEmailAttribute(): ?string
    {
        return $this->user?->email;
    }
}
