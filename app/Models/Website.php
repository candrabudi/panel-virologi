<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        // Identification
        'site_name',
        'site_tagline',
        'site_description',
        
        // Branding
        'site_logo',
        'site_logo_footer',
        'site_favicon',
        
        // Contact
        'site_email',
        'site_phone',
        'site_copyright',
        
        // SEO
        'meta_title',
        'meta_description',
        'meta_keywords',
        
        // Verification / Analytics
        'google_analytics_id',
        'google_console_verification',
        
        // Custom Scripts
        'custom_head_scripts',
        'custom_body_scripts',
    ];

    use HasFactory;
}
