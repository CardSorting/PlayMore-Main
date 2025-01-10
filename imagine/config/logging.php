<?php

use Monolog\Handler\NullHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;
use Monolog\Processor\PsrLogMessageProcessor;

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'deprecations' => [
        'channel' => env('LOG_DEPRECATIONS_CHANNEL', 'null'),
        'trace' => false,
    ],

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'slack'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 14,
            'replace_placeholders' => true,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => env('APP_NAME', 'Laravel') . ' Log',
            'emoji' => ':boom:',
            'level' => env('LOG_LEVEL', 'critical'),
            'replace_placeholders' => true,
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => env('LOG_PAPERTRAIL_HANDLER', SyslogUdpHandler::class),
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
                'connectionString' => 'tls://'.env('PAPERTRAIL_URL').':'.env('PAPERTRAIL_PORT'),
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'level' => env('LOG_LEVEL', 'debug'),
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
            'processors' => [PsrLogMessageProcessor::class],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => env('LOG_LEVEL', 'debug'),
            'facility' => LOG_USER,
            'replace_placeholders' => true,
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => env('LOG_LEVEL', 'debug'),
            'replace_placeholders' => true,
        ],

        'null' => [
            'driver' => 'monolog',
            'handler' => NullHandler::class,
        ],

        'emergency' => [
            'path' => storage_path('logs/laravel.log'),
        ],

        // Custom channels for print orders
        'orders' => [
            'driver' => 'daily',
            'path' => storage_path('logs/orders.log'),
            'level' => 'debug',
            'days' => 90, // Keep order logs for 90 days
            'permission' => 0664,
            'replace_placeholders' => true,
        ],

        'orders-error' => [
            'driver' => 'daily',
            'path' => storage_path('logs/orders-error.log'),
            'level' => 'error',
            'days' => 90,
            'permission' => 0664,
            'replace_placeholders' => true,
        ],

        'metrics' => [
            'driver' => 'daily',
            'path' => storage_path('logs/metrics.log'),
            'level' => 'info',
            'days' => 30, // Keep metrics for 30 days
            'permission' => 0664,
            'replace_placeholders' => true,
        ],

        'exports' => [
            'driver' => 'daily',
            'path' => storage_path('logs/exports.log'),
            'level' => 'debug',
            'days' => 30,
            'permission' => 0664,
            'replace_placeholders' => true,
        ],

        'refunds' => [
            'driver' => 'daily',
            'path' => storage_path('logs/refunds.log'),
            'level' => 'debug',
            'days' => 365, // Keep refund logs for a year
            'permission' => 0664,
            'replace_placeholders' => true,
        ],

        'production' => [
            'driver' => 'daily',
            'path' => storage_path('logs/production.log'),
            'level' => 'debug',
            'days' => 90,
            'permission' => 0664,
            'replace_placeholders' => true,
        ],

        'shipping' => [
            'driver' => 'daily',
            'path' => storage_path('logs/shipping.log'),
            'level' => 'debug',
            'days' => 90,
            'permission' => 0664,
            'replace_placeholders' => true,
        ],

        'notifications' => [
            'driver' => 'daily',
            'path' => storage_path('logs/notifications.log'),
            'level' => 'debug',
            'days' => 30,
            'permission' => 0664,
            'replace_placeholders' => true,
        ],
    ],
];
