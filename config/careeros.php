<?php

return [

    /*
    |--------------------------------------------------------------------------
    | CareerOS Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings specific to CareerOS application functionality.
    |
    */

    // XP System Configuration
    'xp' => [
        'level_threshold' => env('CAREEROS_XP_PER_LEVEL', 1000),
        'max_level' => env('CAREEROS_MAX_LEVEL', 100),
    ],

    // Pagination Settings
    'pagination' => [
        'applications_per_page' => env('CAREEROS_APPS_PER_PAGE', 15),
        'projects_per_page' => env('CAREEROS_PROJECTS_PER_PAGE', 12),
    ],

    // Cache Settings (in minutes)
    'cache' => [
        'portfolio_ttl' => env('CAREEROS_PORTFOLIO_CACHE', 60), // 1 hour
        'stats_ttl' => env('CAREEROS_STATS_CACHE', 30), // 30 minutes
    ],

    // Feature Flags
    'features' => [
        'public_portfolio' => env('CAREEROS_PUBLIC_PORTFOLIO', true),
        'github_integration' => env('CAREEROS_GITHUB_INTEGRATION', false),
        'api_enabled' => env('CAREEROS_API_ENABLED', false),
    ],

    // Portfolio Settings
    'portfolio' => [
        'default_user' => env('CAREEROS_DEFAULT_USER', 'demo@jobhunter.test'),
        'max_featured_projects' => env('CAREEROS_MAX_FEATURED', 6),
    ],

    // Performance Settings
    'performance' => [
        'eager_load_relations' => env('CAREEROS_EAGER_LOAD', true),
        'query_caching' => env('CAREEROS_QUERY_CACHE', false),
        'enable_indexes' => env('CAREEROS_INDEXES', true),
    ],

];
