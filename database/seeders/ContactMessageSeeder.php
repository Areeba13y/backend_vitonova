<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users or create some if none exist
        $users = User::where('role_id', 1)->get();
        
        if ($users->isEmpty()) {
            // Create some test users for contact messages
            $users = collect([
                User::create([
                    'name' => 'John Doe',
                    'email' => 'john.doe@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 1,
                ]),
                User::create([
                    'name' => 'Jane Smith',
                    'email' => 'jane.smith@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 1,
                ]),
                User::create([
                    'name' => 'Bob Wilson',
                    'email' => 'bob.wilson@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 1,
                ]),
            ]);
        }

        // Create contact messages from users
        $messages = [
            'I am interested in your research opportunities. Could you please provide more information?',
            'I would like to know about your team application process and requirements.',
            'I have a question about your upcoming events and how to register.',
            'I am looking for mentorship opportunities in your organization.',
            'I would like to collaborate on a research project with your team.',
            'Can you provide information about your study abroad guidance programs?',
            'I am interested in internship opportunities with your organization.',
            'I have some questions about your event registration process.',
            'I would like to know more about your team structure and current projects.',
            'I am considering applying for a position and would like to learn more.',
        ];

        foreach ($messages as $index => $message) {
            ContactMessage::create([
                'user_id' => $users[$index % $users->count()]->id,
                'message' => $message,
                'is_read' => $index % 3 === 0, // Mark every 3rd message as read
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 5)),
            ]);
        }

        // Create some additional messages with different users
        for ($i = 0; $i < 5; $i++) {
            ContactMessage::create([
                'user_id' => $users->random()->id,
                'message' => 'Additional inquiry message number ' . ($i + 1) . ' regarding various topics.',
                'is_read' => rand(0, 1),
                'created_at' => now()->subDays(rand(1, 15)),
                'updated_at' => now()->subDays(rand(0, 3)),
            ]);
        }
    }
}