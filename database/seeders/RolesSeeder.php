<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleCodes = [
            'contact_inquiry',
            'team_applicant',
            'event_registrant',
            'team_member',
        ];

        foreach ($roleCodes as $code) {
            Role::updateOrCreate(
                ['code' => $code],
                ['name' => Str::title(str_replace('_', ' ', $code))]
            );
        }
    }
}
