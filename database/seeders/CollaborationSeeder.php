<?php

namespace Database\Seeders;

use App\Models\Collaboration;
use Illuminate\Database\Seeder;

class CollaborationSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'organization_name' => 'United Nations Economic and Social Council',
                'subtitle' => 'Partnership for Sustainable Development',
                'description' => 'VNIAS and ECOSOC collaborate to advance sustainable development through research, education, outreach, scholarships, and SDG-focused initiatives.',
                'representative_designation' => 'Minister of Foreign Affairs - USA',
                'representative_name' => 'Dr. Alberto Flores Hernandez',
                'logo' => 'uploads/collaborations/ecosoc-logo.png',
                'is_active' => true,
            ],
            [
                'organization_name' => 'Shaheed Benazir Bhutto University',
                'subtitle' => 'Shaheed Benazir Abad, Sindh, Pakistan',
                'description' => 'This collaboration supports integrated life sciences through conferences, research initiatives, scholarships, exchange programs, and community outreach.',
                'representative_designation' => 'Vice Chancellor',
                'representative_name' => 'Eng. Prof. Dr. Madad Ali Shah',
                'logo' => 'uploads/collaborations/sbbu-logo.jpg',
                'is_active' => true,
            ],
            [
                'organization_name' => 'Positive Plus Foundation',
                'subtitle' => 'Empowering Communities',
                'description' => 'VNIAS and Positive Plus Foundation work together to create learning opportunities through workshops, seminars, mentorship, and leadership development.',
                'representative_designation' => 'Founder',
                'representative_name' => 'Dr. Hossain-Al-Amin',
                'logo' => 'uploads/collaborations/positive-plus-logo.jpg',
                'is_active' => true,
            ],
            [
                'organization_name' => 'Protection of Natural Resources (PNR)',
                'subtitle' => 'Mutual Collaboration in Educational Initiatives',
                'description' => 'This MoU focuses on educational initiatives, training programs, conferences, and research activities for students in life sciences and related fields.',
                'representative_designation' => 'CEO',
                'representative_name' => 'Ms. Arooj Fatima',
                'logo' => 'uploads/collaborations/pnr-logo.png',
                'is_active' => true,
            ],
            [
                'organization_name' => 'African Youth Network',
                'subtitle' => 'Empowering Young Leaders',
                'description' => 'The partnership provides access to research, mentorship, leadership, and career development opportunities for emerging scientists across Africa.',
                'representative_designation' => 'Founder & President',
                'representative_name' => 'Amb. Emmanuel Ferdinand',
                'logo' => 'uploads/collaborations/african-youth-logo.jpg',
                'is_active' => true,
            ],
            [
                'organization_name' => 'Monarchs Matter',
                'subtitle' => 'Youth-led Conservation',
                'description' => 'Monarchs Matter and VNIAS engage youth through biodiversity education, conservation activities, and global environmental leadership programs.',
                'representative_designation' => 'Founder and President',
                'representative_name' => 'Cynthia Zhang',
                'logo' => 'uploads/collaborations/monarchs-matter-logo.png',
                'is_active' => true,
            ],
            [
                'organization_name' => 'UF GenoForge',
                'subtitle' => 'Biotechnology & Bioinformatics',
                'description' => 'This partnership strengthens biotechnology and bioinformatics collaboration through workshops, mentorship, and academic development programs.',
                'representative_designation' => 'Director & CEO',
                'representative_name' => 'Ayesha Farooq',
                'logo' => 'uploads/collaborations/uf-genoforge-logo.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Collaboration::updateOrCreate(
                ['organization_name' => $item['organization_name']],
                $item
            );
        }
    }
}
