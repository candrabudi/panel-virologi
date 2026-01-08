<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = [
            [
                'section_key' => 'hero',
                'section_name' => 'Hero Section',
                'title' => 'Future Protocol.',
                'subtitle' => 'Secure The',
                'description' => 'Our real-time monitoring engine captures millions of signals per second, isolating emerging pathogens and digital threats before they manifest into breaches.',
                'badge_text' => 'System Operational',
                'primary_button_text' => 'Access Grid',
                'primary_button_url' => '#threat-map-section',
                'secondary_button_text' => 'System Status',
                'secondary_button_url' => route('contact'),
                'settings' => [],
                'is_active' => true,
                'order' => 0,
            ],
            [
                'section_key' => 'ebook',
                'section_name' => 'E-Book Section',
                'title' => 'E-Book Library.',
                'subtitle' => null,
                'description' => 'Deep-dive research papers and investigative reports from our global security labs.',
                'badge_text' => 'Strategic Intelligence',
                'primary_button_text' => null,
                'primary_button_url' => null,
                'settings' => [
                    'show_featured' => true,
                    'items_per_row' => 4,
                    'enable_slider' => true,
                ],
                'is_active' => true,
                'order' => 1,
            ],
            [
                'section_key' => 'threatmap',
                'section_name' => 'Threat Map Section',
                'title' => 'Global Cyber Shield.',
                'subtitle' => null,
                'description' => 'Our real-time monitoring engine captures millions of signals per second, isolating emerging pathogens and digital threats before they manifest into breaches.',
                'badge_text' => 'Threat Intelligence',
                'primary_button_text' => 'Access Command Center',
                'primary_button_url' => '/security-hub',
                'settings' => [
                    'enable_live_data' => true,
                    'refresh_interval' => 2000,
                    'show_stats' => true,
                ],
                'is_active' => true,
                'order' => 2,
            ],
            [
                'section_key' => 'product',
                'section_name' => 'Product Solutions Section',
                'title' => 'Security Ecosystem.',
                'subtitle' => null,
                'description' => '"Premium defense architectural patterns designed for the modern enterprise."',
                'badge_text' => 'Enterprise Solutions',
                'primary_button_text' => null,
                'primary_button_url' => null,
                'settings' => [
                    'items_per_row' => 4,
                    'enable_slider' => true,
                    'show_category' => true,
                ],
                'is_active' => true,
                'order' => 3,
            ],
            [
                'section_key' => 'blog',
                'section_name' => 'Intelligence Feed Section',
                'title' => 'Intelligence Feed.',
                'subtitle' => null,
                'description' => null,
                'badge_text' => null,
                'primary_button_text' => 'All Publications',
                'primary_button_url' => route('blog.index'),
                'settings' => [
                    'show_featured' => true,
                    'featured_size' => 'large',
                    'sidebar_items' => 2,
                ],
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($sections as $section) {
            HomeSection::create($section);
        }
    }
}
