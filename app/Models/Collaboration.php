<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collaboration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'logo',
        'organization_name',
        'subtitle',
        'description',
        'representative_designation',
        'representative_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
