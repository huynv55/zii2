<?php
namespace App\Routes;

use FastRoute\RouteCollector;

interface RouteInterface
{
    public function dispatch() : RouteCollector;
}
?>