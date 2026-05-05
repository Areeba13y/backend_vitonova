<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            'Leadership Unit',
            'Secretariat Unit',
            'Media Relations Unit',
            'IT Unit',
            'Policy & Research Unit',
            'Content Writing Unit',
            'Social Media Handling Unit',
            'Scholarship Guidance Unit',
            'Event Organizing & Planning Team',
            'Global Lead Ambassador Unit',
        ];

        foreach ($units as $unitName) {
            $code = Str::of($unitName)
                ->lower()
                ->replaceMatches('/[^a-z0-9\s]/', '')
                ->replaceMatches('/\s+/', '_')
                ->trim('_')
                ->value();

            Unit::updateOrCreate(
                ['code' => $code],
                ['name' => $unitName]
            );
        }
    }
}

