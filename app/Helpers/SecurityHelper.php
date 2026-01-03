<?php

namespace App\Helpers;

class SecurityHelper
{
    /**
     * Clean and sanitize HTML content to prevent XSS.
     * 
     * @param string|null $content
     * @return string|null
     */
    public static function sanitizeHtml(?string $content): ?string
    {
        if (!$content) return null;

        // 1. Strip dangerous tags entirely (including contents)
        $dangerousTags = ['script', 'style', 'iframe', 'object', 'embed', 'applet', 'meta', 'link', 'frame', 'frameset', 'base'];
        foreach ($dangerousTags as $tag) {
            // Match tag with content: <tag>...</tag>
            $content = preg_replace('#<' . $tag . '\b[^>]*>.*?</' . $tag . '>#is', '', $content);
            // Match self-closing or lone tag: <tag />
            $content = preg_replace('#<' . $tag . '\b[^>]*>#is', '', $content);
        }

        // 2. Strip event handlers and hazardous protocols
        $search = [
            '#on\w+\s*=\s*(["\']).*?\1#is',     // onEvent="alert()"
            '#on\w+\s*=\s*[^\s>]*#is',          // onEvent=alert()
            '#javascript:[^"\']*#is',            // javascript:proto
            '#expression\s*\((.*?)\)#is',        // IE expression
        ];

        return preg_replace($search, '', $content);
    }

    /**
     * Deeply clean a string, removing all tags.
     * 
     * @param string|null $string
     * @return string|null
     */
    public static function cleanString(?string $string): ?string
    {
        if (!$string) return null;
        return htmlspecialchars(strip_tags($string), ENT_QUOTES, 'UTF-8');
    }
}
