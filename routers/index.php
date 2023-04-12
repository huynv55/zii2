<?php
/**
 * application router
 */

return function(\FastRoute\RouteCollector $router) {
    

    $router->get('/', function() {
        return ['controller' => 'HomeController', 'action' => 'index'];
    });

    $router->get('/{controller}', function($controller) {
        $controllerClass = ucwords($controller, "_").'Controller';
        return ['controller' => $controllerClass, 'action' => 'index'];
    });
    
    $router->get('/{controller}/{action}', function($controller, $action) {
        $controllerClass = ucwords($controller, "_").'Controller';
        return ['controller' => $controllerClass, 'action' => $action];
    });
};
?>