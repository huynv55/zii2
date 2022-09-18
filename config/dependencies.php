<?php
use App\Application;
use App\Requests\AppRequest;
use DI\Container;
use function FastRoute\simpleDispatcher;
return [
    Application::class => function(Container $c) {
        return new Application($c);
    },
    'Router' => function(Container $c) {
        return simpleDispatcher(function(\FastRoute\RouteCollector $router) {
            require __DIR__.'/routes.php';
        });
    }
];
?>