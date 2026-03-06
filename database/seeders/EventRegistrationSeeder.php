<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Role;
use App\Models\User;
use App\Models\UserEvent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EventRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventRegistrantRoleId = Role::where('code', 'event_registrant')->value('id');

        $interestOptions = [
            'Scholarship Aspirant',
            'Lead Ambassador',
            'Member Research Team',
        ];

        $softSkillOptions = [
            'Graphic Designing',
            'Video Editor',
            'Social Media Handling',
            'Web development',
            'Scientific Writing',
            'N/A',
        ];

        $interpersonalSkillOptions = [
            'Leadership skills',
            'Communication skills',
            'Event Management',
        ];

        $defaultImage = file_exists(public_path('uploads/events/f1fb2116-f889-4d93-a373-bbcabc9db740.png'))
            ? 'uploads/events/f1fb2116-f889-4d93-a373-bbcabc9db740.png'
            : null;

        $events = [
            [
                'category' => 'Technology',
                'title' => 'AI Innovation Summit 2026',
                'description' => 'Explore latest trends in AI, product innovation, and future-ready career skills.',
                'submission_deadline' => now()->addDays(12)->toDateString(),
                'event_date' => now()->addDays(20)->toDateString(),
            ],
            [
                'category' => 'Leadership',
                'title' => 'Youth Leadership Bootcamp 2026',
                'description' => 'Hands-on leadership and communication sessions for high-potential students.',
                'submission_deadline' => now()->addDays(18)->toDateString(),
                'event_date' => now()->addDays(30)->toDateString(),
            ],
        ];

        foreach ($events as $eventIndex => $eventData) {
            $event = Event::updateOrCreate(
                ['title' => $eventData['title']],
                array_merge($eventData, ['image' => $defaultImage])
            );

            for ($i = 1; $i <= 5; $i++) {
                $email = 'event' . ($eventIndex + 1) . '.participant' . $i . '@example.com';

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'role_id' => $eventRegistrantRoleId,
                        'name' => 'Participant ' . $i . ' (Event ' . ($eventIndex + 1) . ')',
                        'password' => Hash::make('password123'),
                        'contact' => '+92-300-0000' . (($eventIndex + 1) * 10 + $i),
                        'address' => 'Street ' . $i . ', City Center',
                        'designation' => 'Event Registrant',
                    ]
                );

                $softSkills = [
                    $softSkillOptions[($eventIndex + $i) % count($softSkillOptions)],
                    $softSkillOptions[($eventIndex + $i + 2) % count($softSkillOptions)],
                ];

                $interpersonalSkills = [
                    $interpersonalSkillOptions[($eventIndex + $i) % count($interpersonalSkillOptions)],
                ];

                UserEvent::updateOrCreate(
                    [
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'university_name' => 'National University ' . $i,
                        'semester_degree' => 'Semester ' . (($i % 8) + 1) . ' - BS Computer Science',
                        'country' => 'Pakistan',
                        'interests' => $interestOptions[($eventIndex + $i) % count($interestOptions)],
                        'soft_skills' => array_values(array_unique($softSkills)),
                        'interpersonal_skills' => $interpersonalSkills,
                        'reason_to_join' => 'I want to gain practical experience, build leadership skills, and contribute to meaningful event initiatives.',
                    ]
                );
            }
        }
    }
}
