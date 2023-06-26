<?php

use FastRoute\Dispatcher;

use function FastRoute\simpleDispatcher;

class ZiiAppFramework {
    protected ?Dispatcher $dispatcher = null;

    protected function router() : Dispatcher
    {
        if (is_null($this->dispatcher)) {
            $func = require __DIR__.'/../routers/index.php';
            $this->dispatcher = simpleDispatcher($func);
        }
        return $this->dispatcher;
    }

    protected function dispatchCli(): array
    {
        $path = $GLOBALS['argv'];
        $controller = 'AppConsole';
        if (!empty($path[1])) {
            $controller = ucwords($path[1], "_").'Console';
        }
        $action = $path[2] ?? 'index';
        $params = [];
        if(count($path) > 3) {
            for ($i = 3; $i < count($path); $i++) {
                $params[] = $path[$i];
            }
        }
        $controller = 'Console\\'.$controller;
        $routeInfo = compact('controller', 'action', 'params');
        $GLOBALS['routeInfo'] = $routeInfo;
        return $routeInfo;
    }
    
    public function dispatch(): array
    {
        if (
            !empty($GLOBALS['routeInfo'])
            and
            is_array($GLOBALS['routeInfo'])
        ) {
            return $GLOBALS['routeInfo'];
        }

        if (is_cli()) {
            return $this->dispatchCli();
        }
        $controller = 'HomeController';
        $action = 'index';
        $params = [];
        $routeInfo = compact('controller', 'action', 'params');
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);
        $routeInfoDispatch = $this->router()->dispatch($httpMethod, $uri);
        switch ($routeInfoDispatch[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // 404 not found
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                // 404 method not allow
                break;
            case \FastRoute\Dispatcher::FOUND:
                $info = [];
                if(is_callable($routeInfoDispatch[1])) {
                    $info = call_user_func_array($routeInfoDispatch[1], $routeInfoDispatch[2] ?? []);
                } else if (is_array($routeInfoDispatch[1])) {
                    $info['controller'] = $routeInfoDispatch[1][0] ?? 'HomeController';
                    $info['action'] = $routeInfoDispatch[1][1] ?? 'index';
                } else if (is_string($routeInfoDispatch[1])) {
                    $tmp = explode('@', $routeInfoDispatch[1]);
                    $info['controller'] = $tmp[0] ?? 'HomeController';
                    $info['action'] = $tmp[1] ?? 'index';
                }
                $routeInfo = array_merge($routeInfo, $info);
                $routeInfo['params'] = $routeInfoDispatch[2] ?? [];
                break;
            default:
                // default action 
                break;
        }
        return $routeInfo;
    }

    /**
     * run application
     *
     * @return void
     */
    public function run() {
        /**
         * start session instance
         */
        session()->start();
        
        $routeInfo = $this->dispatch();
        $GLOBALS['routeInfo'] = $routeInfo;
        $this->makeResponse($routeInfo);
    }

    public function whoops_add_stack_frame(): \Whoops\Run|NULL
    {
        if(!env('APP_DEBUG')) {
            return null;
        }
        $run     = new \Whoops\Run();
        $handler = new \Whoops\Handler\PrettyPageHandler();

        $handler->setApplicationPaths([__FILE__]);

        $handler->addDataTableCallback('Details', function(\Whoops\Exception\Inspector $inspector) {
            $data = array();
            $exception = $inspector->getException();
            $data['Exception class'] = get_class($exception);
            $data['Exception code'] = $exception->getCode();
            return $data;
        });

        $run->pushHandler($handler);
        $run->register();
        return $run;
    }

    /**
     * return response from router dispatch
     *
     * @param array $routeInfo
     * @return void
     */
    private function makeResponse(array $routeInfo)
    {
        $controller = ApplicationLoader::controller($routeInfo['controller']);
        ApplicationLoader::call($controller, $routeInfo['action'], $routeInfo['params']);
    }
}

return new ZiiAppFramework();
?>