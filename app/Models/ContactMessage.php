<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    protected $fillable = ['user_id', 'message', 'is_read'];

    /**
     * Get the user who sent this contact message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sender's name from the related user.
     */
    public function getSenderNameAttribute(): ?string
    {
        return $this->user?->name;
    }

    /**
     * Get the sender's email from the related user.
     */
    public function getSenderEmailAttribute(): ?string
    {
        return $this->user?->email;
    }
}
