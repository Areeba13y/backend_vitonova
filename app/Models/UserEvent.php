<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'user_id',
        'university_name',
        'semester_degree',
        'country',
        'interests',
        'soft_skills',
        'interpersonal_skills',
        'reason_to_join',
    ];

    protected function casts(): array
    {
        return [
            'soft_skills' => 'array',
            'interpersonal_skills' => 'array',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
