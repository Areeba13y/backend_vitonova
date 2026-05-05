<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            UnitSeeder::class,
            UserSeeder::class,
            // EventRegistrationSeeder::class,
            // ContactMessageSeeder::class,
            // TeamApplicationSeeder::class,
        ]);
    }
}
