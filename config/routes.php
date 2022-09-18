<?php
/**
 * config router website
 * 
 * @var \FastRoute\RouteCollector $router
 */
use App\Controllers\Home\IndexAction as HomeIndexAction;

$router->addRoute('GET', '/', HomeIndexAction::class);

?>