<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Page::truncate();
        $pages = [
            [
                'key' => 'blog',
                'title' => 'Blog Index',
                'hero_title' => 'Intelligence Feed.',
                'hero_subtitle' => 'Global Updates',
                'hero_description' => 'Real-time analysis and reports from our global research stations.',
                'meta_title' => 'Intelligence Feed | Virologi',
                'meta_description' => 'Latest updates on pathogen research and cybersecurity trends.',
                'primary_button_text' => 'Latest Reports',
                'primary_button_url' => '#latest',
                'is_active' => true,
            ],
            [
                'key' => 'ebooks',
                'title' => 'E-Books Library',
                'hero_title' => 'Strategic Library.',
                'hero_subtitle' => 'Knowledge Base',
                'hero_description' => 'Deep-dive documentation and whitepapers.',
                'meta_title' => 'E-Books | Virologi',
                'meta_description' => 'Download our premium research papers.',
                'primary_button_text' => 'Browse All',
                'primary_button_url' => '#all',
                'is_active' => true,
            ],
            [
                'key' => 'products',
                'title' => 'Enterprise Solutions',
                'hero_title' => 'Security Ecosystem.',
                'hero_subtitle' => 'Defense Layers',
                'hero_description' => 'Next-generation cybersecurity architectural patterns.',
                'meta_title' => 'Products | Virologi',
                'meta_description' => 'Explore our suite of defensive tools and software.',
                'primary_button_text' => 'View Solutions',
                'primary_button_url' => '#solutions',
                'is_active' => true,
            ],
            [
                'key' => 'services',
                'title' => 'Cyber Security Services',
                'hero_title' => 'Advanced Security <br /> <span class="bg-gradient-to-r from-indigo-500 to-cyan-500 bg-clip-text text-transparent">Expertise Base.</span>',
                'hero_subtitle' => 'Operational Support',
                'hero_description' => 'Mitigasi risiko dan perlindungan infrastruktur digital melalui pendekatan intelijen teknis tingkat tinggi.',
                'meta_title' => 'Layanan Keamanan Siber | Virologi',
                'meta_description' => 'Layanan profesional keamanan siber mulai dari SOC, Audit, hingga Incident Response.',
                'primary_button_text' => 'Lihat Layanan',
                'primary_button_url' => '#explore',
                'is_active' => true,
            ],
            [
                'key' => 'contact',
                'title' => 'Contact Us',
                'hero_title' => 'Get in <br /> <span class="bg-gradient-to-r from-sky-500 to-indigo-600 bg-clip-text text-transparent">Touch.</span>',
                'hero_subtitle' => 'Communication Channels',
                'hero_description' => 'Connect with our technical architects and strategic analysts for security inquiries.',
                'meta_title' => 'Contact Us | Virologi',
                'meta_description' => 'Reach out to our security command center for coordination and support.',
                'primary_button_text' => 'Find Channels',
                'primary_button_url' => '#channels',
                'is_active' => true,
            ],
            [
                'key' => 'leak_check',
                'title' => 'Intelijen Kebocoran Data',
                'hero_title' => 'Periksa <span class="bg-clip-text text-transparent bg-gradient-to-r from-emerald-400 to-cyan-400">Eksposur Data Anda.</span>',
                'hero_subtitle' => 'Mesin Intelijen Pelanggaran',
                'hero_description' => 'Cari miliaran rekam data yang bocor di dark web untuk mengamankan identitas digital Anda.',
                'meta_title' => 'Intelijen Kebocoran Data | RD-VIROLOGI',
                'meta_description' => 'Cari miliaran rekam data yang bocor di dark web untuk mengamankan identitas digital Anda.',
                'primary_button_text' => 'Mulai Pemindaian Mendalam',
                'primary_button_url' => '#scan',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            \App\Models\Page::create($page);
        }
    }
}
