<?php
use App\Application;
use DI\Container;
use function FastRoute\simpleDispatcher;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Log\LoggerInterface;

$settings = require __DIR__."/settings.php";

return [
    'settings' => $settings,
    Application::class => function(Container $c) {
        return new Application($c);
    },
    'Router' => function(Container $c) {
        return simpleDispatcher(function(\FastRoute\RouteCollector $router) {
            require __DIR__.'/routes.php';
        });
    },
    LoggerInterface::class => function (Container $c) {
        $settings = $c->get('settings');

        $loggerSettings = $settings['logger'];
        $logger = new Logger($loggerSettings['name']);

        $processor = new UidProcessor();
        $logger->pushProcessor($processor);

        $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
        $logger->pushHandler($handler);

        return $logger;
    }
];
?>