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
        $this->home();
    }

    private function home()
    {
        $this->route->get('/', HomeIndexAction::class);
        $this->route->post('/post', HomeIndexAction::class);
    }

    public function getUrlRouteHome()
    {
        return self::base().'/';
    }

    public function dispatch(): RouteCollector
    {
        return $this->route;
    }
}
?>