<?php
/**
 * get instance class application
 *
 * @return App\Application
 */
function app() : App\Application
{
    return $GLOBALS[APPLICATION_NAME];
}

/**
 * get instance container
 *
 * @return DI\Container
 */
function container() : DI\Container
{
    return $GLOBALS[CONTAINER_NAME];
}

/**
 * get environment variable value
 *
 * @param string $env
 * @param mixed $default
 * @return mixed
 */
function env(string $env, mixed $default = null) : mixed
{
    if(!empty(getenv($env))) {
        return getenv($env);
    }
    if(!empty($_ENV[$env])) {
        return $_ENV[$env];
    }
    if(!empty($_SERVER[$env])) {
        return $_SERVER[$env];
    }
    return $default;
}

?>