<?php
/**
 * application router
 */

return function(\FastRoute\RouteCollector $router) {
    

    $router->get('/', function() {
        return ['controller' => 'HomeController', 'action' => 'index', 'params' => []];
    });

    $router->get('/{controller}', function($controller) {
        $controllerClass = ucwords($controller, "_").'Controller';
        return ['controller' => $controllerClass, 'action' => 'index', 'params' => []];
    });
    
    $router->get('/{controller}/{action}', function($controller, $action) {
        $controllerClass = ucwords($controller, "_").'Controller';
        return ['controller' => $controllerClass, 'action' => $action, 'params' => []];
    });
};
?>