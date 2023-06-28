<?php
/**
 * application router
 */

return function(\FastRoute\RouteCollector $router) {

    $router->get('/', ['controller' => 'HomeController', 'action' => 'index', 'middlewares' => []]);
    

    //$router->get('/', function() {
    //    return ['controller' => 'HomeController', 'action' => 'index', 'middlewares' => []];
    //});

    $router->get('/{controller}', function($controller) {
        $controllerClass = ucwords($controller, "_").'Controller';
        return ['controller' => $controllerClass, 'action' => 'index', 'middlewares' => []];
    });
    
    $router->get('/{controller}/{action}', function($controller, $action) {
        $controllerClass = ucwords($controller, "_").'Controller';
        return ['controller' => $controllerClass, 'action' => $action, 'middlewares' => []];
    });
};
?>