<?php
namespace App;

use App\Exceptions\AppException;
use App\Exceptions\Routers\MethodNotAllowedException;
use App\Exceptions\Routers\NotFoundException;
use App\Middlewares\MiddlewareAbstract;
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

    public function middleware($middleware, array $params = [])
    {
        $settings = $this->getConfig();
        $middlewares = $settings['middlewares'];
        if(is_string($middleware))
        {
            if(!empty($middlewares[$middleware]) && is_string($middlewares[$middleware]))
            {
                return $this->getContainer()->call($middlewares[$middleware].'::handle', $params);
            } else if(!empty($middlewares[$middleware]) && is_array($middlewares[$middleware])) 
            {
                foreach ($middlewares[$middleware] as $key => $m) {
                    if(!$this->middleware($m, $params)) 
                    {
                        return MiddlewareAbstract::STOP;
                    }
                }
                return MiddlewareAbstract::NEXT;
            }
            return $this->getContainer()->call($middleware.'::handle', $params);
            
        } else if(is_array($middleware))
        {
            foreach ($middleware as $key => $m) {
                if(!$this->middleware($m, $params)) 
                {
                    return MiddlewareAbstract::STOP;
                }
            }
            return MiddlewareAbstract::NEXT;
        }
        return MiddlewareAbstract::NEXT;
    }

    /**
     * run application
     *
     * @return void
     */
    public function run()
    {
        $debugExeption = $this->whoops_add_stack_frame();
        try {
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
                    throw new NotFoundException('404 Not Found');
                    break;
                case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                    throw new MethodNotAllowedException('405 Method Not Allowed');
                    break;
                case \FastRoute\Dispatcher::FOUND:
                    $this->makeResponse($routeInfo[1], $routeInfo[2] ?? []);
                    break;
            }
        }
        catch (\Throwable $e)
        {
            $this->log()->error($e->getMessage());
            $this->log()->error($e->getTraceAsString());
            if($e instanceof  AppException){
                $e->handler();
            }
            if($debugExeption) $debugExeption->handleException($e);
            // TODO catch default exception
            view()->setViewRender('Exceptions/default')->withData(['error' => $e])->send();
        }
        
    }

    public function makeResponse($handler, array $vars = [])
    {
        $this->container->call($handler, $vars);
    }

    public function whoops_add_stack_frame()
    {
        if(!env('APP_DEBUG'))
        {
            return null;
        }
        $run     = new \Whoops\Run();
        $handler = new \Whoops\Handler\PrettyPageHandler();

        $handler->setApplicationPaths([__FILE__]);

        $handler->addDataTableCallback('Details', function(\Whoops\Exception\Inspector $inspector) {
            $data = array();
            $exception = $inspector->getException();
            if ($exception instanceof SomeSpecificException) {
                $data['Important exception data'] = $exception->getSomeSpecificData();
            }
            $data['Exception class'] = get_class($exception);
            $data['Exception code'] = $exception->getCode();
            return $data;
        });

        $run->pushHandler($handler);

        // Example: tag all frames inside a function with their function name
        $run->pushHandler(function ($exception, $inspector, $run) {
            $inspector->getFrames()->map(function ($frame) {
                if ($function = $frame->getFunction()) {
                    $frame->addComment("This frame is within function '$function'", 'whoops');
                }
                return $frame;
            });
        });
        $run->register();
        return $run;
    }
}

?>