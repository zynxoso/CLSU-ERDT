<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HistoryTimelineItem;
use App\Models\HistoryAchievement;
use App\Models\HistoryContentBlock;

class HistoryContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Timeline Items
        $timelineItems = [
            [
                'title' => 'ERDT Establishment',
                'description' => 'Establishment of ERDT through the Department of Science and Technology (DOST) to address the need for high-level engineering human resources in the Philippines. The program was initially a consortium of seven universities offering graduate scholarships in various engineering fields.',
                'event_date' => '2007-01-01',
                'year_label' => '2007',
                'category' => 'milestone',
                'color' => 'blue',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'CLSU Joins ERDT',
                'description' => 'CLSU joined the ERDT consortium, expanding the program\'s reach to Central Luzon and bringing its expertise in agricultural engineering and water resources management. This marked the beginning of CLSU\'s commitment to advancing engineering research and education in the region.',
                'event_date' => '2012-01-01',
                'year_label' => '2012',
                'category' => 'milestone',
                'color' => 'blue',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Research Focus Expansion',
                'description' => 'CLSU-ERDT expanded its research focus to include sustainable energy systems, smart agriculture technology, and environmental engineering solutions for climate change adaptation. This broadened scope allowed for more interdisciplinary research and innovation.',
                'event_date' => '2015-01-01',
                'year_label' => '2015',
                'category' => 'achievement',
                'color' => 'blue',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Major Research Breakthroughs',
                'description' => 'CLSU-ERDT scholars and faculty achieved major breakthroughs in water resource management and agricultural engineering, with several patents filed and research papers published in high-impact journals. These achievements highlighted the program\'s contribution to national development.',
                'event_date' => '2018-01-01',
                'year_label' => '2018',
                'category' => 'achievement',
                'color' => 'blue',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Digital Innovation',
                'description' => 'CLSU-ERDT launched initiatives focusing on digital agriculture, IoT applications in farming, and AI-driven solutions for agricultural productivity and sustainability. These cutting-edge projects represent the program\'s commitment to embracing new technologies and addressing contemporary challenges.',
                'event_date' => '2020-01-01',
                'year_label' => '2020+',
                'category' => 'milestone',
                'color' => 'blue',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($timelineItems as $item) {
            HistoryTimelineItem::create($item);
        }

        // Seed Achievements
        $achievements = [
            [
                'title' => 'Human Capital Development',
                'description' => 'Graduated over 200 MS and PhD scholars who now contribute to academia, industry, and government agencies across the Philippines, creating a critical mass of engineering experts driving national development.',
                'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                'statistic' => '200+',
                'statistic_label' => 'graduates',
                'color' => 'blue',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Research Publications',
                'description' => 'Published over 50 research papers in international journals, contributing to the global body of knowledge in engineering and establishing CLSU as a recognized research institution in agricultural engineering.',
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'statistic' => '50+',
                'statistic_label' => 'papers',
                'color' => 'blue',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Technology Development',
                'description' => 'Developed 15+ patented technologies addressing local and national challenges, including water management systems, agricultural machinery, and sustainable energy solutions tailored to Philippine conditions.',
                'icon' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
                'statistic' => '15+',
                'statistic_label' => 'patents',
                'color' => 'blue',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Collaborations',
                'description' => 'Established collaborations with 12 universities across the Philippines and international research institutions, creating a network of expertise and resources that enhances the quality and impact of engineering research.',
                'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
                'statistic' => '12',
                'statistic_label' => 'universities',
                'color' => 'blue',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($achievements as $achievement) {
            HistoryAchievement::create($achievement);
        }

        // Seed Content Blocks
        $contentBlocks = [
            // Hero Section
            [
                'section' => 'hero',
                'key' => 'title',
                'value' => 'Our History',
                'type' => 'text',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'section' => 'hero',
                'key' => 'subtitle',
                'value' => 'The journey of CLSU-ERDT in advancing engineering education and research',
                'type' => 'text',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'section' => 'hero',
                'key' => 'background_image',
                'value' => 'storage/bg/bgloginscholar.png',
                'type' => 'image',
                'sort_order' => 3,
                'is_active' => true,
            ],

            // Introduction Section
            [
                'section' => 'introduction',
                'key' => 'title',
                'value' => 'Building Engineering Excellence',
                'type' => 'text',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'section' => 'introduction',
                'key' => 'paragraph_1',
                'value' => 'The Engineering Research and Development for Technology (ERDT) program was established through the Department of Science and Technology (DOST) to address the need for high-level engineering human resources in the Philippines. What began as a consortium of seven universities has grown into a nationwide network of excellence in engineering education and research.',
                'type' => 'text',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'section' => 'introduction',
                'key' => 'paragraph_2',
                'value' => 'Central Luzon State University joined the ERDT consortium in 2012, bringing its expertise in agricultural engineering and water resources management to the program. Since then, CLSU-ERDT has been at the forefront of developing innovative solutions to address local and national challenges.',
                'type' => 'text',
                'sort_order' => 3,
                'is_active' => true,
            ],

            // Vision Section
            [
                'section' => 'vision',
                'key' => 'title',
                'value' => 'Our Vision for the Future',
                'type' => 'text',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'section' => 'vision',
                'key' => 'description',
                'value' => 'CLSU-ERDT aims to become a leading center for engineering innovation in Southeast Asia, focusing on sustainable development solutions for agricultural communities and environmental challenges. Our strategic vision includes:',
                'type' => 'text',
                'sort_order' => 2,
                'is_active' => true,
            ],

            // Contact Section
            [
                'section' => 'contact',
                'key' => 'title',
                'value' => 'Need Assistance?',
                'type' => 'text',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'section' => 'contact',
                'key' => 'email',
                'value' => 'erdt@clsu.edu.ph',
                'type' => 'text',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'section' => 'contact',
                'key' => 'phone',
                'value' => '0920-9312126',
                'type' => 'text',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'section' => 'contact',
                'key' => 'office_address',
                'value' => 'CLSU-ERDT Office, Engineering Building<br>Central Luzon State University<br>Science City of Muñoz, Nueva Ecija',
                'type' => 'html',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'section' => 'contact',
                'key' => 'office_hours',
                'value' => 'Monday to Friday, 8:00 AM to 5:00 PM',
                'type' => 'text',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($contentBlocks as $block) {
            HistoryContentBlock::create($block);
        }
    }
}
