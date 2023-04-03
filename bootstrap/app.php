<?php 

class ZiiAppFramework {
	
	public function dispatch(): array
	{
		if (!is_cli()) {
			$path = explode('/', $_SERVER['REQUEST_URI']);
		} else {
			$path = $GLOBALS['argv'];
		}
		
		$controller = 'HomeController';
		if (!empty($path[1])) {
			$controller = ucwords($path[1], "_").'Controller';
		}
		$action = $path[2] ?? 'index';
		$params = [];
		if(count($path) > 3) {
			for ($i = 3; $i < count($path); $i++) {
				$params[] = $path[$i];
			}
		}
		return compact('controller', 'action', 'params');
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

	/**
	 * return response from router dispatch
	 *
	 * @param array $routeInfo
	 * @return void
	 */
	private function makeResponse(array $routeInfo)
	{
		$controller = ApplicationLoader::controller($routeInfo['controller']);
		$controllerClass = get_class($controller);
		if ( method_exists($controllerClass, $routeInfo['action']) ) {
			$reflection = new ReflectionMethod($controllerClass, $routeInfo['action']);
			$numberOfParameter = $reflection->getNumberOfParameters();
			$numberOfRequiredParameter = $reflection->getNumberOfRequiredParameters();
			$params = array_splice($routeInfo['params'], 0, $numberOfParameter);
			if (count($params) >= $numberOfRequiredParameter) {
				$reflection->invokeArgs($controller, $params);
			} else {
				throw new \Exception('Can not run Application');
			}
		} else {
			throw new \Exception('Method '.$controllerClass.'::'.$routeInfo['action'].' not found');
		}
	}
}

return new ZiiAppFramework();
?>