<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeamMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMemberRoleId = Role::where('code', 'team_member')->value('id');

        // Team members data grouped by unit name
        $teamMembers = [
            'Leadership Unit' => [
                [
                    'name' => 'Mr. Ghulam Murtaza',
                    'email' => 'ghulam.murtaza@vitanova.org',
                    'designation' => 'CEO & President',
                    'details' => 'Center for Agricultural Biochemistry and Biotechnology - CABB',
                    'contact' => null,
                    'address' => null,
                ],
                // [
                //     'name' => 'Dr. M. Shahzad Zafar',
                //     'email' => 'shahzad.zafar@vitanova.org',
                //     'designation' => 'Senior Vice President',
                //     'details' => 'Shenzhen University, China',
                //     'contact' => null,
                //     'address' => null,
                // ],
                // [
                //     'name' => 'Miss. Hafsa Sarfraz',
                //     'email' => 'hafsa.sarfraz@vitanova.org',
                //     'designation' => 'Vice President',
                //     'details' => 'University of Agriculture Faisalabad, Pakistan',
                //     'contact' => null,
                //     'address' => null,
                // ],
                // [
                //     'name' => 'Nupur Akhter',
                //     'email' => 'nupur.akhter@vitanova.org',
                //     'designation' => 'Head of Collaborations',
                //     'details' => 'University of Science and Technology Chittagong, Bangladesh',
                //     'contact' => null,
                //     'address' => null,
                // ],
                // [
                //     'name' => 'Aisha',
                //     'email' => 'aisha@vitanova.org',
                //     'designation' => 'General Secretary',
                //     'details' => 'University of Sindh, Pakistan',
                //     'contact' => null,
                //     'address' => null,
                // ],
            ],
            // 'Secretariat Unit' => [
            //     [
            //         'name' => 'Ruqia',
            //         'email' => 'ruqia@vitanova.org',
            //         'designation' => 'Record Keeping Secretary',
            //         'details' => 'University of Agriculture Faisalabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Dr. Alveena Zahra',
            //         'email' => 'alveena.zahra@vitanova.org',
            //         'designation' => 'Peer Mentor',
            //         'details' => 'Jinnah Woman University Karachi, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Dr. Wazir Ali',
            //         'email' => 'wazir.ali@vitanova.org',
            //         'designation' => 'Community Outreach Partner',
            //         'details' => 'Shaheed Benazir Bhutto University, Shaheed Benazirabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Dr. Aitaj Ashgarova',
            //         'email' => 'aitaj.ashgarova@vitanova.org',
            //         'designation' => 'Public Speaker & Coordinator',
            //         'details' => 'Scientific Research Institute of Fruit and Tea Growing, Azerbaijan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Dr. Mouna',
            //         'email' => 'mouna@vitanova.org',
            //         'designation' => 'Research Coordinator',
            //         'details' => 'Hassan II University of Casablanca, Morocco',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Shakeela Anjum',
            //         'email' => 'shakeela.anjum@vitanova.org',
            //         'designation' => 'Secretariat Coordinator',
            //         'details' => 'University of Baltistan, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ],
            // 'Media Relations Unit' => [
            //     [
            //         'name' => 'Ayesha Nazir',
            //         'email' => 'ayesha.nazir@vitanova.org',
            //         'designation' => 'Media Relations Officer',
            //         'details' => 'Bahaudin Zakriya University Multan, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Susan Aestino',
            //         'email' => 'susan.aestino@vitanova.org',
            //         'designation' => 'Graphic Designer',
            //         'details' => 'VitaNova International Alliance for Sciences',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Aleeshbah Amir',
            //         'email' => 'aleeshbah.amir@vitanova.org',
            //         'designation' => 'Graphic Designer',
            //         'details' => 'National University of Medical Sciences',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Philip Anthony',
            //         'email' => 'philip.anthony@vitanova.org',
            //         'designation' => 'Graphic Designer',
            //         'details' => 'Biotechnology Department, Federal University Dutse',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Zainab Shahid',
            //         'email' => 'zainab.shahid@vitanova.org',
            //         'designation' => 'Graphic Designer',
            //         'details' => 'Quid e Azam University, Islamabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Wajeeha Ahmed Khattak',
            //         'email' => 'wajeeha.khattak@vitanova.org',
            //         'designation' => 'Volunteer Graphic Designer',
            //         'details' => 'Fazaia Degree College, ARF PAC Kamra',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ],
            // 'IT Unit' => [
            //     [
            //         'name' => 'Rabia Soomro',
            //         'email' => 'rabia.soomro@vitanova.org',
            //         'designation' => 'Director IT Unit',
            //         'details' => 'Sukkur IBA University',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Areeba Yasir',
            //         'email' => 'areeba.yasir@vitanova.org',
            //         'designation' => 'Deputy Director',
            //         'details' => 'Fatima Jinnah Woman University Rawalpindi',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Usman Sarwar',
            //         'email' => 'usman.sarwar@vitanova.org',
            //         'designation' => 'Systems & Integration Coordinator',
            //         'details' => 'Sukkur IBA University',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Muhammad Bilal',
            //         'email' => 'bilal.fiaz@vitanova.org',
            //         'designation' => 'Backend Operations Developer',
            //         'details' => 'Government College University Faisalabad',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Sibgha Mursaleen',
            //         'email' => 'sibgha.mursaleen@vitanova.org',
            //         'designation' => 'Digital Platforms Coordinator',
            //         'details' => 'Sukkur IBA University',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Sajia Ashfaq',
            //         'email' => 'sajia.ashfaq@vitanova.org',
            //         'designation' => 'UX & Interface Coordinator',
            //         'details' => 'Jinnah University for Women, Karachi',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ],
            // 'Policy & Research Unit' => [
            //     [
            //         'name' => 'Dr. Muhammad Ahmad',
            //         'email' => 'muhammad.ahmad@vitanova.org',
            //         'designation' => 'Research Projects Manager',
            //         'details' => 'China Agriculture University, Beijing, China',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Elnara Mutelim',
            //         'email' => 'elnara.mutelim@vitanova.org',
            //         'designation' => 'Head of Researchers',
            //         'details' => 'Scientific Research Institute of Fruit and Tea Growing, Azerbaijan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Rafia Noor ul Ain',
            //         'email' => 'rafia.noor@vitanova.org',
            //         'designation' => 'Research Content Writer',
            //         'details' => 'Arid Agriculture University, Rawalpindi, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Babar Ali',
            //         'email' => 'babar.ali@vitanova.org',
            //         'designation' => 'Research Project Leader',
            //         'details' => 'University of Agriculture Faisalabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Sabahat Noor',
            //         'email' => 'sabahat.noor@vitanova.org',
            //         'designation' => 'Researcher',
            //         'details' => 'Rawalpindi Women University, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Hina Fatima',
            //         'email' => 'hina.fatima@vitanova.org',
            //         'designation' => 'Researcher',
            //         'details' => 'University of Agriculture Faisalabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Dr. Syed Hassan',
            //         'email' => 'syed.hassan@vitanova.org',
            //         'designation' => 'Researcher',
            //         'details' => 'University of Malakand, Balochistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Amina Rafique',
            //         'email' => 'amina.rafique@vitanova.org',
            //         'designation' => 'Graduate Student (Biotechnology)',
            //         'details' => 'School of Biochemistry and Biotechnology, University of the Punjab, Lahore, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ],
            // 'Content Writing Unit' => [
            //     [
            //         'name' => 'Toufique',
            //         'email' => 'toufique@vitanova.org',
            //         'designation' => 'Senior Technical Content Writer',
            //         'details' => 'Sindh Agriculture University, Tandojam, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Hina Sarwar',
            //         'email' => 'hina.sarwar@vitanova.org',
            //         'designation' => 'Content Writer',
            //         'details' => 'University of Narowal',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Hafsa Noor',
            //         'email' => 'hafsa.noor@vitanova.org',
            //         'designation' => 'Content Writer',
            //         'details' => null,
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ],
            // 'Social Media Handling Unit' => [
            //     [
            //         'name' => 'Noshaba Hassan',
            //         'email' => 'noshaba.hassan@vitanova.org',
            //         'designation' => 'Director Social Media',
            //         'details' => 'Women University Multan, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Mansoor ul Haq',
            //         'email' => 'mansoor.haq@vitanova.org',
            //         'designation' => 'Deputy Director Social Media',
            //         'details' => 'Abdul Wali khan University, KPK, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Arooj Fatima',
            //         'email' => 'arooj.fatima@vitanova.org',
            //         'designation' => 'Technical Manager',
            //         'details' => 'University of Central Punjab',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Maryam Shahzadi',
            //         'email' => 'maryam.shahzadi@vitanova.org',
            //         'designation' => 'Assistant Manager',
            //         'details' => 'University of Agriculture Faisalabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Pirah Arif',
            //         'email' => 'pirah.arif@vitanova.org',
            //         'designation' => 'Assistant Manager',
            //         'details' => 'IBA Sukkur University, Sindh',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Riya Jamshid',
            //         'email' => 'riya.jamshid@vitanova.org',
            //         'designation' => 'SEO Manager',
            //         'details' => 'University of Central Punjab, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ],
            // 'Scholarship Guidance Unit' => [
            //     [
            //         'name' => 'Dr. Ghulam Murtaza',
            //         'email' => 'ghulam.murtaza.scholar@vitanova.org',
            //         'designation' => 'Scholarship Advisor',
            //         'details' => 'Chinese Academy of Sciences, China',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Shehryar Farid',
            //         'email' => 'shehryar.farid@vitanova.org',
            //         'designation' => 'Scholarship Advisor',
            //         'details' => 'University of Galway, Ireland',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Kashif Ali',
            //         'email' => 'kashif.ali@vitanova.org',
            //         'designation' => 'Danube AgriFood Management Erasmus Mundus Program',
            //         'details' => 'Hungarian University of Agriculture and Life Sciences, Hungary & University of Zagreb, Croatia',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Abdullah Shoukat',
            //         'email' => 'abdullah.shoukat@vitanova.org',
            //         'designation' => 'MS Smart Agriculture',
            //         'details' => 'Atatürk üniversitesi Türkiye',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Abdul Hafeez',
            //         'email' => 'abdul.hafeez@vitanova.org',
            //         'designation' => 'MS Ecology',
            //         'details' => 'Northeast Normal University, China',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ],
            // 'Event Organizing & Planning Team' => [
            //     [
            //         'name' => 'Neha Aas Muhammad',
            //         'email' => 'neha.aas@vitanova.org',
            //         'designation' => 'Technical Event Manager',
            //         'details' => 'Govt. College University, Faisalabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Romesa Sajid',
            //         'email' => 'romesa.sajid@vitanova.org',
            //         'designation' => 'Marketing Manager',
            //         'details' => 'Air University, Islamabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Rimsha Zahir Ali Shah',
            //         'email' => 'rimsha.zahir@vitanova.org',
            //         'designation' => 'Public Speaker & Host',
            //         'details' => null,
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Rozia Gul',
            //         'email' => 'rozia.gul@vitanova.org',
            //         'designation' => 'Event Coordinator',
            //         'details' => 'University of Agriculture Faisalabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Iqra Faisal',
            //         'email' => 'iqra.faisal@vitanova.org',
            //         'designation' => 'Email Manager',
            //         'details' => 'Quid-I-Azam University, Islamabad',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Yusra',
            //         'email' => 'yusra@vitanova.org',
            //         'designation' => 'Academic Coordinator',
            //         'details' => 'Fatima Jinnah Woman University',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ],
            // 'Global Lead Ambassador Unit' => [
            //     [
            //         'name' => 'Hira Khalid',
            //         'email' => 'hira.khalid@vitanova.org',
            //         'designation' => 'Lead Ambassador',
            //         'details' => 'Liaqat University of Health and Medical Sciences',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Asma Ahmed',
            //         'email' => 'asma.ahmed@vitanova.org',
            //         'designation' => 'Lead Ambassador',
            //         'details' => 'Quid-I-Azam University, Islamabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Zahra Batool',
            //         'email' => 'zahra.batool@vitanova.org',
            //         'designation' => 'Lead Ambassador',
            //         'details' => 'University of Lahore',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Maryam Hayat',
            //         'email' => 'maryam.hayat@vitanova.org',
            //         'designation' => 'Lead Ambassador',
            //         'details' => 'Bahaudin Zakriya University, Multan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Eman Tariq',
            //         'email' => 'eman.tariq@vitanova.org',
            //         'designation' => 'Lead Ambassador',
            //         'details' => 'Fatima Jinnah Woman University, Rawalpindi',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Adil Rasool',
            //         'email' => 'adil.rasool@vitanova.org',
            //         'designation' => 'Lead Ambassador',
            //         'details' => 'University of Agriculture Faisalabad, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Ibrahim Buhari Yunusa',
            //         'email' => 'ibrahim.buhari@vitanova.org',
            //         'designation' => 'Nigeria International Coordinator',
            //         'details' => 'Federal Dutse University, Nigeria',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Fathima Zanooja',
            //         'email' => 'fathima.zanooja@vitanova.org',
            //         'designation' => 'Sri Lanka Coordinator',
            //         'details' => 'The Punjab University',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Ibrahim Zakari Ubale',
            //         'email' => 'ibrahim.zakari@vitanova.org',
            //         'designation' => 'Lead Ambassador',
            //         'details' => 'Federal Dutse University, Nigeria',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Mujahid Khan',
            //         'email' => 'mujahid.khan@vitanova.org',
            //         'designation' => 'Lead Ambassador',
            //         'details' => 'University of Kohat, KPK, Pakistan',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            //     [
            //         'name' => 'Gulzar Ali',
            //         'email' => 'gulzar.ali@vitanova.org',
            //         'designation' => 'Chemist | Co-Lead Ambassador, UoS Chapter',
            //         'details' => 'Dr. M.A. Kazi Institute of Chemistry, University of Sindh',
            //         'contact' => null,
            //         'address' => null,
            //     ],
            // ], 
        ];

        foreach ($teamMembers as $unitName => $members) {
            $unit = Unit::where('name', $unitName)->first();
            if (!$unit) {
                $this->command->warn("Unit '{$unitName}' not found. Skipping members for this unit.");
                continue;
            }

            foreach ($members as $memberData) {
                User::updateOrCreate(
                    ['email' => $memberData['email']],
                    array_merge($memberData, [
                        'role_id' => $teamMemberRoleId,
                        'unit_id' => $unit->id,
                        'password' => Hash::make('password123'),
                    ])
                );
            }
        }

        $this->command->info('Team members seeded successfully!');
    }
}
