<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContactSetting;

class ContactSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContactSetting::truncate();

        ContactSetting::create([
            'hero_badge' => 'Operational Node // Comms Hub',
            'hero_title' => 'Get in <br /> <span class="bg-gradient-to-r from-sky-500 to-indigo-600 bg-clip-text text-transparent">Touch.</span>',
            'hero_description' => 'No forms. Just direct, verified communication channels for strategic partnerships and high-priority technical support.',
            
            'channels' => [
                [
                    'id' => 1,
                    'title' => 'Email Inquiries',
                    'value' => 'contact@rd-virologi.com',
                    'icon' => 'email',
                    'description' => 'For general inquiries, partnership proposals, and administrative matters.',
                    'link' => 'mailto:contact@rd-virologi.com',
                    'color' => 'sky'
                ],
                [
                    'id' => 2,
                    'title' => 'Technical Helpdesk',
                    'value' => '+62 821 1234 5678',
                    'icon' => 'phone',
                    'description' => 'Direct line for active clients requiring urgent technical deployment or node support.',
                    'link' => 'tel:+6282112345678',
                    'color' => 'indigo'
                ],
                [
                    'id' => 3,
                    'title' => 'Command Center',
                    'value' => "Cyber Park Tower, Floor 12\nJakarta, Indonesia",
                    'icon' => 'location',
                    'description' => 'Our core operational facility and distributed node coordination hub.',
                    'link' => '#',
                    'color' => 'slate'
                ]
            ],

            'social_title' => 'Follow the <br class="hidden sm:block" /> <span class="bg-gradient-to-r from-sky-400 to-indigo-400 bg-clip-text text-transparent">Intelligence Feed.</span>',
            'social_description' => 'Stay connected with our real-time updates and tactical briefings across all major secure networks.',
            
            'seo_title' => 'Contact | RD-VIROLOGI Operational Hub',
            'seo_description' => 'Connect with RD-VIROLOGI technical architects and analysts. Verified communication channels for high-consequence security.',
            'seo_keywords' => ['contact cybersecurity', 'soc support', 'incident response contact', 'security partnership']
        ]);
    }
}
