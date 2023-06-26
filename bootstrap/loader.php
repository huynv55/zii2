<?php
/**
 * interface class initialize loader
 */
interface initializeLoader
{
    public function initialize();
}

class ApplicationLoader
{
    public static function has(string $class): bool
    {
        return (
            !empty($GLOBALS[$class])
            and
            $GLOBALS[$class] instanceof initializeLoader
        );
    }

    public static function set(string $class, initializeLoader $instance)
    {
        $GLOBALS[$class] = $instance;
    }

    public static function get(string $class): initializeLoader
    {
        if(self::has($class)) {
            return $GLOBALS[$class];
        }
        $GLOBALS[$class] = self::make($class);
        return $GLOBALS[$class];
    }

    public static function make(string $class): ?initializeLoader
    {
        if (!class_exists($class)) {
            throw new \Exception($class." not exists");
        }
        $ref = new ReflectionClass($class);
        if (!in_array('initializeLoader', $ref->getInterfaceNames())) {
            throw new \Exception($class." must implements initializeLoader");
        }
        $contructor = $ref->getConstructor();
        if (is_null($contructor)) {
            $instance = new $class();
        } else {
            $params = [];
            foreach ($contructor->getParameters() as $key => $param) {
                $type = $param->getType()->getName();
                if(class_exists($type)) {
                    $params[] = self::get($type);
                }
                else if($param->isOptional()) {
                    $params[] = $param->getDefaultValue();
                }
            }
            $instance = $ref->newInstanceArgs($params);
        }
        $instance?->initialize();
        return $instance;
    }

    /**
     * call method by $instance
     *
     * @param initializeLoader $instance
     * @param string $method
     * @param array $params
     * @return mixed
     * 
     * @throws Exception if method not found in class
     */
    public static function call(initializeLoader $instance, string $method, array $params = []): mixed
    {
        $class = get_class($instance);
        if ( method_exists($class, $method) ) {
            $reflection = new ReflectionMethod($class, $method);
            $paramsFormMethod = [];
            foreach ($reflection->getParameters() as $key => $param) {
                $type = $param->getType()->getName();
                $name = $param->getName();
                if(class_exists($type)) {
                    $paramsFormMethod[$name] = self::get($type);
                }
                else if (in_array($param->getName(), $params)) {
                    $paramsFormMethod[$name] = $params[$name];
                }
                else if($param->isOptional()) {
                    $paramsFormMethod[$name] = $param->getDefaultValue();
                }
            }
            return $reflection->invokeArgs($instance, $paramsFormMethod);
        } 
        else {
            throw new \Exception('Method '.$method.'::'.$class.' not found');
        }
    }

    public static function model(string $modelClass)
    {
        if(strpos($modelClass, 'App\\Models\\') === 0) {
            return self::get($modelClass);
        } else {
            return self::get('App\\Models\\'.$modelClass);
        }
    }

    public static function controller(string $controllerClass)
    {
        if(strpos($controllerClass, 'App\\Controllers\\') === 0) {
            return self::get($controllerClass);
        } else {
            return self::get('App\\Controllers\\'.$controllerClass);
        }
    }

    public static function request(string $requestClass)
    {
        if(strpos($requestClass, 'App\\Requests\\') === 0) {
            return self::get($requestClass);
        } else {
            return self::get('App\\Requests\\'.$requestClass);
        }
    }

    public static function response(string $responseClass)
    {
        if(strpos($responseClass, 'App\\Responses\\') === 0) {
            return self::get($responseClass);
        } else {
            return self::get('App\\Responses\\'.$responseClass);
        }
    }

    public static function service(string $serviceClass)
    {
        if(strpos($serviceClass, 'App\\Services\\') === 0) {
            return self::get($serviceClass);
        } else {
            return self::get('App\\Services\\'.$serviceClass);
        }   
    }

    public static function repository(string $repositoryClass)
    {
        if(strpos($repositoryClass, 'App\\Repositories\\') === 0) {
            return self::get($repositoryClass);
        } else {
            return self::get('App\\Repositories\\'.$repositoryClass);
        }
    }
}
?>