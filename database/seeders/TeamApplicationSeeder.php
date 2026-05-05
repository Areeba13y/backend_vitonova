<?php

namespace Database\Seeders;

use App\Models\TeamApplication;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeamApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users or create some if none exist
        $users = User::where('role_id', 1)->get();
        
        if ($users->isEmpty()) {
            // Create some test users for team applications
            $users = collect([
                User::create([
                    'name' => 'Alice Johnson',
                    'email' => 'alice.johnson@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 1,
                ]),
                User::create([
                    'name' => 'Charlie Brown',
                    'email' => 'charlie.brown@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 1,
                ]),
                User::create([
                    'name' => 'Diana Prince',
                    'email' => 'diana.prince@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 1,
                ]),
                User::create([
                    'name' => 'Edward Norton',
                    'email' => 'edward.norton@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 1,
                ]),
                User::create([
                    'name' => 'Fiona Apple',
                    'email' => 'fiona.apple@example.com',
                    'password' => Hash::make('password123'),
                    'role_id' => 1,
                ]),
            ]);
        }

        // Create sample resume content
        $resumeContent = "John Doe\nSoftware Developer\nEmail: john.doe@example.com\nPhone: (555) 123-4567\n\nExperience:\n- 5 years of software development\n- Full stack developer\n- Team lead experience\n\nSkills:\n- PHP, Laravel\n- JavaScript, Vue.js\n- MySQL, PostgreSQL\n- Git, Docker\n\nEducation:\n- BSc Computer Science, University of Example";

        // Create team applications
        $applications = [
            [
                'position' => 'Research Positions',
                'resume_content' => $resumeContent,
                'status' => 'pending',
            ],
            [
                'position' => 'Internships',
                'resume_content' => str_replace('John Doe', 'Jane Smith', $resumeContent),
                'status' => 'approved',
            ],
            [
                'position' => 'Study Abroad Guidance',
                'resume_content' => str_replace('John Doe', 'Bob Wilson', $resumeContent),
                'status' => 'pending',
            ],
            [
                'position' => 'Mentorship Program',
                'resume_content' => str_replace('John Doe', 'Alice Johnson', $resumeContent),
                'status' => 'rejected',
            ],
            [
                'position' => 'Research Positions',
                'resume_content' => str_replace('John Doe', 'Charlie Brown', $resumeContent),
                'status' => 'pending',
            ],
            [
                'position' => 'Internships',
                'resume_content' => str_replace('John Doe', 'Diana Prince', $resumeContent),
                'status' => 'approved',
            ],
            [
                'position' => 'Study Abroad Guidance',
                'resume_content' => str_replace('John Doe', 'Edward Norton', $resumeContent),
                'status' => 'pending',
            ],
            [
                'position' => 'Mentorship Program',
                'resume_content' => str_replace('John Doe', 'Fiona Apple', $resumeContent),
                'status' => 'pending',
            ],
        ];

        foreach ($applications as $index => $app) {
            // Create a fake resume file
            $resumeFileName = 'resume_' . ($index + 1) . '.pdf';
            $resumePath = 'resumes/' . $resumeFileName;
            
            // Create the resumes directory if it doesn't exist
            if (!Storage::exists('resumes')) {
                Storage::makeDirectory('resumes');
            }
            
            // Store fake resume content
            Storage::put($resumePath, $app['resume_content']);

            TeamApplication::create([
                'user_id' => $users[$index % $users->count()]->id,
                'position' => $app['position'],
                'resume_path' => $resumePath,
                'resume_original_name' => $resumeFileName,
                'status' => $app['status'],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(0, 5)),
            ]);
        }

        // Create some additional applications
        for ($i = 0; $i < 5; $i++) {
            $resumeFileName = 'resume_additional_' . ($i + 1) . '.pdf';
            $resumePath = 'resumes/' . $resumeFileName;
            Storage::put($resumePath, "Additional resume content for applicant " . ($i + 1));

            TeamApplication::create([
                'user_id' => $users->random()->id,
                'position' => ['Research Positions', 'Internships', 'Study Abroad Guidance', 'Mentorship Program'][rand(0, 3)],
                'resume_path' => $resumePath,
                'resume_original_name' => $resumeFileName,
                'status' => ['pending', 'approved', 'rejected'][rand(0, 2)],
                'created_at' => now()->subDays(rand(1, 15)),
                'updated_at' => now()->subDays(rand(0, 3)),
            ]);
        }
    }
}