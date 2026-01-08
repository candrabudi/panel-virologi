<?php

namespace Database\Seeders;

use App\Models\CyberSecurityService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CyberSecurityServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Security Operations Center (SOC) Managed Services',
                'short_name' => 'Managed SOC',
                'category' => 'soc',
                'summary' => '24/7 continuous monitoring and response to cyber threats.',
                'description' => '<p>Our Managed SOC provides round-the-clock security monitoring, detection, and response services. We use advanced SIEM and EDR tools to protect your digital assets.</p>',
                'service_scope' => ['24/7 Monitoring', 'Incident Response', 'Threat Hunting', 'Log Analysis'],
                'deliverables' => ['Monthly Security Report', 'Real-time Alerting', 'Incident Post-Mortem'],
                'target_audience' => ['Enterprises', 'Financial Institutions', 'Government Agencies'],
                'ai_keywords' => ['SOC', 'monitoring', 'incident response', 'SIEM'],
                'ai_domain' => 'soc',
                'thumbnail' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&q=80&w=800',
            ],
            [
                'name' => 'Professional Penetration Testing',
                'short_name' => 'PenTest',
                'category' => 'pentest',
                'summary' => 'Identifying vulnerabilities before hackers do.',
                'description' => '<p>Comprehensive security assessment of your network, web applications, and mobile apps using industry-standard methodologies.</p>',
                'service_scope' => ['Web App Testing', 'Network PenTest', 'Mobile App Security', 'Social Engineering'],
                'deliverables' => ['Vulnerability Report', 'Remediation Guide', 'Executive Summary'],
                'target_audience' => ['SaaS Providers', 'E-commerce', 'IT Companies'],
                'ai_keywords' => ['pentest', 'vulnerability', 'hacking', 'security audit'],
                'ai_domain' => 'pentest',
                'thumbnail' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&q=80&w=800',
            ],
            [
                'name' => 'Cyber Security Audit & Compliance',
                'short_name' => 'CS Audit',
                'category' => 'audit',
                'summary' => 'Ensuring your systems meet international security standards.',
                'description' => '<p>Assessment of your security posture against frameworks like ISO 27001, NIST, and local regulations.</p>',
                'service_scope' => ['GAP Analysis', 'Policy Review', 'Compliance Audit', 'Risk Assessment'],
                'deliverables' => ['Compliance Certificate', 'Audit Report', 'Policy Templates'],
                'target_audience' => ['Regulated Industries', 'Critical Infrastructure'],
                'ai_keywords' => ['audit', 'compliance', 'ISO27001', 'NIST'],
                'ai_domain' => 'governance',
                'thumbnail' => 'https://images.unsplash.com/photo-1454165833767-027508496b41?auto=format&fit=crop&q=80&w=800',
            ],
            [
                'name' => 'Incident Response & Digital Forensics',
                'short_name' => 'IR / DFIR',
                'category' => 'incident_response',
                'summary' => 'Rapid response to security breaches and thorough investigation.',
                'description' => '<p>Expert team focused on neutralizing threats, containing breaches, and performing deep-dive forensics to understand the root cause.</p>',
                'service_scope' => ['Breach Containment', 'Forensic Analysis', 'Evidence Collection', 'Recovery Support'],
                'deliverables' => ['Forensic Report', 'Root Cause Analysis', 'Evidence Log'],
                'target_audience' => ['Breached Organizations', 'Legal Firms'],
                'ai_keywords' => ['forensics', 'incident response', 'data breach', 'investigation'],
                'ai_domain' => 'incident_response',
                'thumbnail' => 'https://images.unsplash.com/photo-1558494949-ef0109121c64?auto=format&fit=crop&q=80&w=800',
            ],
        ];

        foreach ($services as $svc) {
            $svc['slug'] = Str::slug($svc['name']);
            $svc['seo_title'] = $svc['name'] . ' | RD-VIROLOGI';
            $svc['seo_description'] = $svc['summary'];
            $svc['seo_keywords'] = $svc['ai_keywords'];
            
            CyberSecurityService::updateOrCreate(
                ['slug' => $svc['slug']],
                $svc
            );
        }
    }
}
