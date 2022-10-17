<?php
namespace App\Routes;

use App\Controllers\Home\IndexAction as HomeIndexAction;
use FastRoute\RouteCollector;

class AppRoute extends Route
{
    protected RouteCollector $route;

    public function __construct(RouteCollector $route)
    {
        $this->route = $route;
    }

    public static function getUrlRouteHome()
    {
        return '/';
    }

    public function dispatch(): RouteCollector
    {
        $this->route->get(self::getUrlRouteHome(), HomeIndexAction::class);
        return $this->route;
    }
}
?>