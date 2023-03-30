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

    public static function get(string $class): initializeLoader
    {
        if(self::has($class)) {
            return $GLOBALS[$class];
        }
        $GLOBALS[$class] = self::make($class);
        return $GLOBALS[$class];
    }

    public static function make(string $class): initializeLoader
    {
        if (!class_exists($class)) {
            throw new \Exception($class." not exists");
        }
        $instance = new $class();
        $instance->initialize();
        return $instance;
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