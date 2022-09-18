<?php
namespace App;

use App\Responses\ResponseInterface;
use DI\Container;

class Application 
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getContainer() : Container
    {
        return $this->container;
    }

    public function run()
    {
        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $dispatcher = $this->container->get('Router');

        [$route, $handler, $vars] = $dispatcher->dispatch($httpMethod, $uri);
        switch ($route) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                echo '404 Not Found';

                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                echo '405 Method Not Allowed';
                break;
            case \FastRoute\Dispatcher::FOUND:
                echo $this->makeResponse($handler, $vars);
                break;
        }
    }

    public function makeResponse($handler, array $vars = [])
    {
        if(is_string($handler)) {
            $handler = explode('::', $handler);
        }
        if(is_array($handler)) {
            if(count($handler) == 2) {
                $controller = $this->container->make($handler[0]);
                return call_user_func_array(array($controller, $handler[1]), $vars);
            } else if(count($handler) == 1) {
                $controller = $this->container->make($handler[0]);
                return call_user_func_array(array($controller, 'response'), $vars);
            } else {
                return json_encode($handler);
            }   
        } else {
            return $handler;
        }
        
    }
}

?>