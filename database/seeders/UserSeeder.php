<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMemberRoleId = Role::where('code', 'team_member')->value('id');

        User::updateOrCreate(
            ['email' => 'admin@web.com'],
            [
                'role_id' => $teamMemberRoleId,
                'name' => 'Admin',
                'password' => Hash::make(Str::random(16)),
                'designation' => 'Administrator',
            ]
        );
    }
}
