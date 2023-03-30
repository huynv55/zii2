<?php 

class ZiiAppFramework {
	
	public function dispatch(): array
	{
		$path = explode('/', $_SERVER['REQUEST_URI']);
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

	public function run() {
		$routeInfo = $this->dispatch();
		$GLOBALS['routeInfo'] = $routeInfo;
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