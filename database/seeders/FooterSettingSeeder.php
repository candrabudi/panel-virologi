<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FooterSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\FooterSetting::create([
            'description' => 'Pioneering the intersection of digital infrastructure and biological security. Our mission is to safeguard the future through advanced research and defensive technologies.',
            'address' => 'Research Station 7, Antarctica',
            'email' => 'secure@rd-virologi.com',
            'copyright_text' => '© 2026 RD-VIROLOGI. All rights reserved.',
            'social_links' => [
                'twitter' => '#',
                'linkedin' => '#',
                'github' => '#',
            ],
            'column_1_title' => 'Research',
            'column_1_links' => [
                ['text' => 'Pathogen Analysis', 'url' => '#'],
                ['text' => 'Viral Genomics', 'url' => '#'],
                ['text' => 'Defense Systems', 'url' => '#'],
            ],
            'column_2_title' => 'Company',
            'column_2_links' => [
                ['text' => 'About Us', 'url' => '#'],
                ['text' => 'Careers', 'url' => '#'],
                ['text' => 'Contact', 'url' => '#'],
            ],
            'column_3_title' => 'Legal',
            'column_3_links' => [
                ['text' => 'Privacy Policy', 'url' => '#'],
                ['text' => 'Terms of Service', 'url' => '#'],
            ],
        ]);
    }
}
