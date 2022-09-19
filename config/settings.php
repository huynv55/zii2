<?php
use Monolog\Level;

return [
    'app_env' => 'local',
    'di_compilation_path' => __DIR__ . '/../tmp/cache',
    'display_error_details' => false,
    'log_errors' => true,
    
    'logger' => [
        'name' => 'zii2-app',
        'path' => __DIR__ . '/../tmp/logs/app.log',
        'level' => Level::Debug,
    ],
];
?>