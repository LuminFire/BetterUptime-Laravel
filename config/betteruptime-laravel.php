<?php

return [
    'monitor' => [
        'enabled' => env('BETTER_UPTIME_MONITOR_ENABLED', true),
        'path' => env('BETTER_UPTIME_MONITOR_PATH', 'better-uptime'),
    ],

    'heartbeat' => [
        'enabled' => env('BETTER_UPTIME_HEARTBEAT_ENABLED', true),
        'url' => env('BETTER_UPTIME_HEARTBEAT_URL'),
        'minutes' => env('BETTER_UPTIME_HEARTBEAT_FREQUENCY', 5),

        'retry' => [
            'count' => env('BETTER_UPTIME_HEARTBEAT_RETRY_COUNT', 5),
            'delay' => env('BETTER_UPTIME_HEARTBEAT_RETRY_DELAY', 3000),
        ],
    ],
];
