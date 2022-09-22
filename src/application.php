<?php
namespace App;

use DI\Container;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class Application 
{
    protected Container $container;
    protected array $attributes;
    protected array $responseHeaders;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->attributes = [];
        $this->responseHeaders = [];
    }

    public function setResponseHeader(string $header, string $value)
    {
        $this->responseHeaders[$header] = $value;
    }

    public function getResponseHeader(string $header) : string
    {
        return $this->responseHeaders[$header];
    }

    public function getResponseHeaders() : array
    {
        return $this->responseHeaders;
    }

    public function setAttribute(string $attribute, mixed $value)
    {
        $this->attributes[$attribute] = $value;
    }

    public function getAttribute(string $attribute) : mixed
    {
        return $this->attributes[$attribute] ?? null;
    }

    public function getAttributes() : array
    {
        return $this->attributes;
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

    public function middleware($middleware)
    {
        $settings = $this->getConfig();

        if(is_array($middleware))
        {
            foreach($middleware as $m) {
                if(!empty($settings['middlewares'][$m]))
                {
                    if(is_string($settings['middlewares'][$m]))
                    {
                        if(!$this->getContainer()->call($settings['middlewares'][$m].'::handle'))
                        {
                            break;
                        }
                    } else if(is_array($settings['middlewares'][$m]))
                    {
                        foreach($settings['middlewares'][$m] as $h)
                        {
                            if(is_string($h)) {
                                if(!$this->getContainer()->call($h.'::handle'))
                                {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        } else if(is_string($middleware)) {
            if(is_string($settings['middlewares'][$middleware])) {
                $this->getContainer()->call($settings['middlewares'][$middleware].'::handle');
            } else if(is_array($settings['middlewares'][$middleware]))
            {
                foreach($settings['middlewares'][$middleware] as $h)
                {
                    if(is_string($h)) {
                        if(!$this->getContainer()->call($h.'::handle'))
                        {
                            break;
                        }
                    }
                }
            }
        }
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
                die();
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                echo '405 Method Not Allowed';
                die();
                break;
            case \FastRoute\Dispatcher::FOUND:
                $this->makeResponse($routeInfo[1], $routeInfo[2] ?? []);
                break;
        }
    }

    public function makeResponse($handler, array $vars = [])
    {
        $this->container->call($handler, $vars);
    }
}

?>