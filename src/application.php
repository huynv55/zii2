<?php
namespace App;

use App\Responses\ResponseInterface;
use DI\Container;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

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

    public function log() : Logger
    {
        return $this->container->get(LoggerInterface::class);
    }

    public function getConfig(): array
    {
        return $this->container->get('settings');
    }

    public function getRouter()
    {
        return $this->container->get('Router');
    }

    /**
     * run application
     *
     * @return void
     */
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

        $routeInfo = $this->getRouter()->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                echo '404 Not Found';

                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                echo '405 Method Not Allowed';
                break;
            case \FastRoute\Dispatcher::FOUND:
                echo $this->makeResponse($routeInfo[1], $routeInfo[2] ?? []);
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