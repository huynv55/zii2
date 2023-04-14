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
		$routeInfo = compact('controller', 'action', 'params');
		return $routeInfo;
	}
	
	public function dispatch(): array
	{
		if (!empty($GLOBALS['routeInfo']) and is_array($GLOBALS['routeInfo']))
		{
			return $GLOBALS['routeInfo'];
		}
		if (is_cli())
		{
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
		$routeInfo2 = $this->router()->dispatch($httpMethod, $uri);
		switch ($routeInfo2[0]) {
			case \FastRoute\Dispatcher::NOT_FOUND:
				// 404 not found
				break;
			case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				// 404 method not allow
				break;
			case \FastRoute\Dispatcher::FOUND:
				$info = [];
				if(is_callable($routeInfo2[1])) {
					$info = call_user_func_array($routeInfo2[1], $routeInfo2[2] ?? []);
				} else if (is_array($routeInfo2[1])) {
					$info['controller'] = $routeInfo2[1][0] ?? 'HomeController';
					$info['action'] = $routeInfo2[1][1] ?? 'index';
				} else if (is_string($routeInfo2[1])) {
					$tmp = explode('@', $routeInfo2[1]);
					$info['controller'] = $tmp[0] ?? 'HomeController';
					$info['action'] = $tmp[1] ?? 'index';
				}
				$routeInfo = array_merge($routeInfo, $info);
				$routeInfo['params'] = $routeInfo2[2] ?? [];
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