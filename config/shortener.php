<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Reserved Slugs
    |--------------------------------------------------------------------------
    |
    | These slugs are reserved for system use and cannot be used for short links.
    | Includes single/double characters and common system routes.
    |
    */
    'reserved_slugs' => [
        // Single characters (reserved for future use)
        'a', 'i', 'x', 'v',
        
        // Two characters - Common brands & abbreviations
        'fb', 'ig', 'tw', 'yt', 'go', 'my', 'id', 'us', 'uk', 'cn',
        'ai', 'io', 'js', 'db', 'ui', 'ux', 'qa', 'hr', 'pr', 'pm',
        
        // Three+ characters - System routes
        'api', 'app', 'dev', 'www', 'cdn', 'img', 'css', 'old', 'new',
        'admin', 'login', 'logout', 'register', 'dashboard', 'settings',
        'password', 'reset', 'verify', 'confirm', 'email',
        'links', 'link', 'shorten', 'url', 'short',
        'folders', 'folder', 'tags', 'tag',
        'user', 'users', 'profile', 'account',
        
        // Marketing & Public pages
        'about', 'help', 'faq', 'contact', 'support',
        'terms', 'privacy', 'legal', 'dmca',
        'blog', 'news', 'press', 'media',
        'pricing', 'plans', 'pro', 'free', 'premium',
        'features', 'products', 'docs', 'guide',
        
        // Utility routes
        'health', 'status', 'test', 'demo',
        'assets', 'static', 'public', 'storage',
        'upload', 'download', 'export', 'import',
        'report', 'abuse', 'flag', 'spam',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Slug Length Settings
    |--------------------------------------------------------------------------
    |
    | random_slug_length: Length for auto-generated slugs (default: 6)
    | min_custom_slug_length: Minimum length for user-defined custom slugs
    |
    */
    'random_slug_length' => 6,
    'min_custom_slug_length' => 3,
    
    /*
    |--------------------------------------------------------------------------
    | Analytics Retention
    |--------------------------------------------------------------------------
    |
    | Number of days to retain click analytics data
    |
    */
    'analytics_days' => 30,
];
