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

function camelCase($str, array $noStrip = [])
{
    // non-alpha and non-numeric characters become spaces
    $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
    $str = trim($str);
    // uppercase the first character of each word
    $str = ucwords($str);
    $str = str_replace(" ", "", $str);
    $str = lcfirst($str);
    return $str;
}

function from_camel_case($input) {
    $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
    preg_match_all($pattern, $input, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ?
        strtolower($match) :
        lcfirst($match);
    }
    return implode('_', $ret);
}

?>