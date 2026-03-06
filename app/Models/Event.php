<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image',
        'category',
        'title',
        'description',
        'submission_deadline',
        'event_date',
    ];

    protected function casts(): array
    {
        return [
            'submission_deadline' => 'date',
            'event_date' => 'date',
        ];
    }

    public function registrations()
    {
        return $this->hasMany(UserEvent::class);
    }
}
