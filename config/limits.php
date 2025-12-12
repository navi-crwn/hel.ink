<?php

return [
    'ip' => [
        'creates_per_minute' => env('LIMIT_IP_PER_MINUTE', 10),
        'creates_per_day' => env('LIMIT_IP_PER_DAY', 200),
    ],
    'guest' => [
        'max_links_per_day' => env('LIMIT_GUEST_PER_DAY', 50),
        'max_total_links' => env('LIMIT_GUEST_TOTAL', 200),
    ],
    'user' => [
        'max_links_per_day' => env('LIMIT_USER_PER_DAY', 200),
        'max_active_links' => env('LIMIT_USER_ACTIVE', 1000),
    ],
];
