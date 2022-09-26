<?php
/**
 * config router website
 * 
 * @var \FastRoute\RouteCollector $router
 */
use App\Routes\AppRoute;

$router = (new AppRoute($router))->dispatch();

?>