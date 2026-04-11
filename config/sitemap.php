<?php

return [
    'exclude_prefixes' => [
        'api',
        'company',
        'qr',
        'public',
        'models',
        'sanctum',
        'otp',
        'storage',
        'superadmin',
        'test',
    ],

    'exclude_uris' => [
        'robots.txt',
        'sitemap.xml',
        'up',
        'login',
        'register',
        'forgot-password',
        'reset-password',
        'verify-otp',
        'dashboard',
        'profile',
    ],

    'exclude_contains' => [
        'test-',
        'test/',
    ],

    'vms_countries' => [
        'india',
        'uk',
        'usa',
    ],

    'cache_minutes' => 60,

    'state_path' => public_path('sitemap-state.json'),

    'state_url' => '/sitemap-state.json',

    'request_timeout_seconds' => 3,
];
