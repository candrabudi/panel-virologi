<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'og_title',
        'og_description',
        'og_image',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getThumbnailAttribute($value)
    {
        if (!$value) return null;
        
        // If it's already a full URL (legacy or external), return it
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return asset('storage/' . $value);
    }

    public function categories()
    {
        return $this->belongsToMany(
            ArticleCategory::class,
            'article_category_pivot'
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            ArticleTag::class,
            'article_tag_pivot'
        );
    }
}
