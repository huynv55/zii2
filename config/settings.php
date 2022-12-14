<?php
use Monolog\Level;
use App\Middlewares\AppMiddleware;
use App\Middlewares\CorsMiddleware;

return [
    'app' => [
        'env' => env('APP_ENV', 'local'),
        'name' => env('APP_NAME', 'Zii2')
    ],
    'router_cached_path' => __DIR__ . '/../tmp/router.php',
    'display_error_details' => false,
    'log_errors' => true,
    'smtp' => [
        'host' => env('SMTP_HOST'),
        'port' => env('SMTP_PORT'),
        'user' => env('SMTP_USER'),
        'sercret' => env('SMTP_SERCRET'),
        'secure' => env('SMTP_SECURE')
    ],
    'mimes' => [
        'zip' => [
            'application/x-zip',
            'application/zip',
            'application/x-zip-compressed',
            'application/s-compressed',
            'multipart/x-zip',
        ],
        'pdf' => [
            'application/pdf',
            'application/force-download',
            'application/x-download'
        ]
    ],
    'logger' => [
        'name' => 'zii2-app',
        'path' => __DIR__ . '/../tmp/logs/app.log',
        'level' => Level::Debug,
    ],
    'database' => [
        'mysql' => [
            'host' => env('MYSQL_HOST'),
            'user' => env('MYSQL_USER'),
            'password' => env('MYSQL_PASSWORD'),
            'port' => env('MYSQL_PORT'),
            'db' => env('MYSQL_DB'),
            'charset' => env('MYSQL_CHARSET', 'utf8mb4')
        ],
        'mongo' => [
            'host' => 'localhost',
            'port' => 27017
        ]
    ],
    'cookie' => [
        // setting cookie
    ],
    'middlewares' => [
        'app' => [
            AppMiddleware::class,
            CorsMiddleware::class
        ]
    ]
];
?>