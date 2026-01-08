<?php

namespace database\seeders;

use App\Models\AboutSetting;
use Illuminate\Database\Seeder;

class AboutSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AboutSetting::truncate();
        AboutSetting::create([
            'hero_badge' => 'Operational Identity // Since 2004',
            'hero_title' => 'Tentang <br /> <span class="bg-gradient-to-r from-sky-500 to-indigo-600 bg-clip-text text-transparent">Kami.</span>',
            'hero_description' => 'virologi.info — Cybersecurity Underground Since 2004',
            'hero_image' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=1920',

            'story_title' => 'Cybersecurity Underground Since 2004',
            'story_content' => '<p><strong>virologi.info</strong> lahir pada tahun <strong>2004</strong>, ketika internet masih liar, gelap, dan belum sepenuhnya dijinakkan oleh algoritma, sensor, serta kepentingan komersial.</p>
<p>Kami muncul dari kedalaman kode—tempat virus diciptakan, sistem diuji, dan batas keamanan terus dipertanyakan.</p>
<p>Berawal dari eksplorasi <strong>hacking</strong> dan <strong>virus komputer</strong>, kami mempelajari bagaimana sistem digital bisa runtuh, dan yang lebih penting, bagaimana cara menjaganya tetap berdiri. Dari proses itulah virologi.info tumbuh menjadi ruang pembelajaran yang <strong>kritis, teknis, dan independen</strong>.</p>
<p>Hari ini, virologi.info berevolusi menjadi <strong>komunitas cybersecurity, teknologi, dan artificial intelligence (AI)</strong> yang berdiri di garis depan ancaman siber modern.</p>
<p>Di dunia di mana <strong>data adalah senjata</strong>, <strong>AI adalah kekuatan</strong>, dan <strong>manusia sering tertinggal</strong>, kami hadir sebagai ruang bagi mereka yang memilih <strong>memahami sistem sebelum dikendalikan olehnya</strong>.</p>
<p>virologi.info bukan sekadar komunitas. Ini adalah <strong>node pengetahuan</strong> bagi pelajar, profesional IT, peneliti, dan praktisi keamanan siber yang percaya bahwa <strong>pemahaman adalah fondasi pertahanan digital</strong>.</p>',
            'story_image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=800',

            'vision_title' => 'Manifesto',
            'vision_content' => 'Kami tidak menjual ilusi keamanan. Kami membangun kesadaran. Karena di dunia yang dikendalikan oleh sistem, mereka yang memahami kode adalah mereka yang bertahan.',
            
            'mission_title' => 'Apa yang Kami Bahas',
            'mission_items' => [
                'Keamanan siber dan ancaman digital nyata',
                'Hacking dan eksploitasi sebagai sarana pembelajaran',
                'Artificial Intelligence (AI), automasi, dan dampaknya terhadap manusia',
                'Privasi, data, dan kontrol sistem',
                'Teknologi sebagai alat bertahan hidup di era digital'
            ],

            'stats' => [
                ['title' => 'Network nodes', 'value' => '20', 'suffix' => 'Y+'],
                ['title' => 'Intelligence Files', 'value' => '1.5', 'suffix' => 'K+'],
                ['title' => 'Active Operators', 'value' => '250', 'suffix' => '+'],
                ['title' => 'Uptime History', 'value' => '99', 'suffix' => '%']
            ],

            'core_values' => [
                [
                    'title' => 'Intelligence Driven',
                    'description' => 'Keputusan kami didasarkan pada data dan intelijen teknis yang akurat.',
                    'icon' => 'brain'
                ],
                [
                    'title' => 'Strategic Resilience',
                    'description' => 'Membangun sistem yang tidak hanya bertahan, tapi juga pulih dengan cepat.',
                    'icon' => 'shield'
                ],
                [
                    'title' => 'Continuous Evolution',
                    'description' => 'Selalu selangkah lebih maju dari ancaman yang terus berkembang.',
                    'icon' => 'refresh'
                ]
            ],

            'team_members' => [],

            'seo_title' => 'Tentang Kami | virologi.info — Cybersecurity Underground Since 2004',
            'seo_description' => 'Pelajari sejarah dan manifesto virologi.info dalam membangun pertahanan siber yang kritis, teknis, dan independen.',
            'seo_keywords' => ['sejarah virologi info', 'cybersecurity underground', 'hacking indonesia', 'security awareness']
        ]);
    }
}
