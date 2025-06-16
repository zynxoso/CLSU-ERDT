<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FacultyMember;

class FacultyMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facultyMembers = [
            [
                'name' => 'Dr. Sylvester A. Badua',
                'position' => 'Graduate Faculty Member',
                'department' => 'Biological and Agricultural Engineering',
                'specialization' => 'Machine Systems, Mechanization, Instrumentation',
                'education_background' => 'Doctor of Philosophy in Biological and Agricultural Engineering from Kansas State University',
                'research_description' => 'Develops innovative agricultural mechanization solutions and precision agriculture technologies for sustainable farming systems.',
                'photo_path' => 'experts/image.png',
                'expertise_tags' => ['Machine Systems', 'Mechanization', 'Instrumentation', 'Precision Agriculture'],
                'degree_level' => 'Ph.D',
                'university_origin' => 'Kansas State University',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Dr. Alejandro Robles',
                'position' => 'Professor',
                'department' => 'Biological and Agricultural Engineering',
                'specialization' => 'Land, Air, Water Resources and Environmental Engineering',
                'education_background' => 'Ph.D from Washington State University',
                'research_description' => 'Research focuses on sustainable water management systems and environmental impact assessment of agricultural practices.',
                'photo_path' => 'experts/image copy.png',
                'expertise_tags' => ['Land Resources', 'Air Quality', 'Water Resources', 'Environmental Engineering'],
                'degree_level' => 'Ph.D',
                'university_origin' => 'Washington State University',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Dr. Maria Santos',
                'position' => 'Associate Professor',
                'department' => 'Biological and Agricultural Engineering',
                'specialization' => 'Machinery systems, precision agriculture, soil and water management',
                'education_background' => 'Ph.D from Kansas State University',
                'research_description' => 'Develops innovative agricultural mechanization solutions for small-scale farmers in the Philippines.',
                'photo_path' => 'experts/image copy 2.png',
                'expertise_tags' => ['Machinery Systems', 'Precision Agriculture', 'Soil Management', 'Water Management'],
                'degree_level' => 'Ph.D',
                'university_origin' => 'Kansas State University',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Dr. Carlos Mendoza',
                'position' => 'Professor',
                'department' => 'Civil Engineering',
                'specialization' => 'Structural Engineering and Earthquake Resistant Design',
                'education_background' => 'Ph.D from University of Tokyo',
                'research_description' => 'Focuses on developing resilient infrastructure solutions for disaster-prone areas in the Philippines.',
                'photo_path' => 'experts/image copy 3.png',
                'expertise_tags' => ['Structural Engineering', 'Earthquake Design', 'Infrastructure', 'Disaster Resilience'],
                'degree_level' => 'Ph.D',
                'university_origin' => 'University of Tokyo',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Dr. Elena Reyes',
                'position' => 'Associate Professor',
                'department' => 'Electrical Engineering',
                'specialization' => 'Renewable Energy Systems and Smart Grid Technology',
                'education_background' => 'Ph.D from University of California, Berkeley',
                'research_description' => 'Researches sustainable energy solutions for rural communities and off-grid applications.',
                'photo_path' => 'experts/image copy 4.png',
                'expertise_tags' => ['Renewable Energy', 'Smart Grid', 'Power Systems', 'Rural Electrification'],
                'degree_level' => 'Ph.D',
                'university_origin' => 'University of California, Berkeley',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Dr. Roberto Cruz',
                'position' => 'Professor',
                'department' => 'Computer Engineering',
                'specialization' => 'Artificial Intelligence and Machine Learning',
                'education_background' => 'Ph.D from Stanford University',
                'research_description' => 'Develops AI solutions for agricultural automation and smart farming technologies.',
                'photo_path' => 'experts/image copy 5.png',
                'expertise_tags' => ['Artificial Intelligence', 'Machine Learning', 'Computer Vision', 'Smart Agriculture'],
                'degree_level' => 'Ph.D',
                'university_origin' => 'Stanford University',
                'is_active' => true,
                'sort_order' => 6
            ],
            [
                'name' => 'Dr. Jennifer Lim',
                'position' => 'Associate Professor',
                'department' => 'Chemical Engineering',
                'specialization' => 'Process Engineering and Biotechnology',
                'education_background' => 'Ph.D from Massachusetts Institute of Technology',
                'research_description' => 'Focuses on sustainable chemical processes and biotechnology applications in agriculture.',
                'photo_path' => 'experts/image copy 6.png',
                'expertise_tags' => ['Process Engineering', 'Biotechnology', 'Sustainable Chemistry', 'Bio-processes'],
                'degree_level' => 'Ph.D',
                'university_origin' => 'Massachusetts Institute of Technology',
                'is_active' => true,
                'sort_order' => 7
            ],
            [
                'name' => 'Dr. Michael Torres',
                'position' => 'Professor',
                'department' => 'Mechanical Engineering',
                'specialization' => 'Thermodynamics and Energy Systems',
                'education_background' => 'Ph.D from Georgia Institute of Technology',
                'research_description' => 'Researches energy-efficient systems and renewable energy integration for industrial applications.',
                'photo_path' => 'experts/image copy 7.png',
                'expertise_tags' => ['Thermodynamics', 'Energy Systems', 'Heat Transfer', 'Energy Efficiency'],
                'degree_level' => 'Ph.D',
                'university_origin' => 'Georgia Institute of Technology',
                'is_active' => true,
                'sort_order' => 8
            ]
        ];

        foreach ($facultyMembers as $facultyData) {
            FacultyMember::create($facultyData);
        }
    }
}
