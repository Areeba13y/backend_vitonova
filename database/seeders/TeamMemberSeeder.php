<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMemberRoleId = Role::where('code', 'team_member')->value('id');

        if (! $teamMemberRoleId) {
            $this->command?->warn("Role 'team_member' not found. Team members were not seeded.");
            return;
        }

        $teamMembers = require __DIR__ . '/team_members.php';

        if (! is_array($teamMembers) || $teamMembers === []) {
            $this->command?->warn('Team member source data is empty. Nothing was seeded.');
            return;
        }

        User::query()
            ->where('role_id', $teamMemberRoleId)
            ->forceDelete();

        foreach ($teamMembers as $memberData) {
            $unitName = $memberData['unit_name'] ?? null;

            if (! $unitName) {
                $this->command?->warn("Unit name missing for {$memberData['name']}. Skipping this record.");
                continue;
            }

            $unit = Unit::firstOrCreate(
                ['name' => $unitName],
                [
                    'code' => Str::of($unitName)
                        ->lower()
                        ->replaceMatches('/[^a-z0-9\s]/', '')
                        ->replaceMatches('/\s+/', '_')
                        ->trim('_')
                        ->value(),
                ]
            );

            User::create([
                'role_id' => $teamMemberRoleId,
                'unit_id' => $unit->id,
                'name' => $memberData['name'],
                'email' => $memberData['email'],
                'password' => 'password123',
                'contact' => $memberData['contact'] ?? null,
                'address' => $memberData['address'] ?? null,
                'designation' => $memberData['designation'] ?? null,
                'profile_picture' => $memberData['profile_picture'] ?? null,
                'details' => $memberData['details'] ?? null,
            ]);
        }

        $this->command?->info('Team members seeded successfully!');
    }
}
