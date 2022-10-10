<?php
use App\Responses\ResponseHTML;
use App\Responses\ResponseJSON;
use App\Responses\ResponseRedirect;
use App\Services\SessionService;

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
    return app()->getContainer();
}

/**
 * get instance session
 *
 * @return DI\SessionService
 */
function session() : SessionService
{
    return container()->get(SessionService::class);
}

/**
 * get view render
 *
 * @return ResponseHTML
 */
function view() : ResponseHTML
{
    return container()->get(ResponseHTML::class);
}

/**
 * get json response
 *
 * @return ResponseJSON
 */
function responseJson() : ResponseJSON
{
    return container()->get(ResponseJSON::class);
}

/**
 * get instance redirect
 *
 * @return ResponseRedirect
 */
function redirect() : ResponseRedirect
{
    return container()->get(ResponseRedirect::class);
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

function createSlug(string $string) : string
{
    $string = preg_replace('/[^a-zA-Z0-9_ -]/s',' ',$string);
    $table = array(
        'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
        'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
        'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
        'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
        'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
        'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
        'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
        'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
    );
    // -- Remove duplicated spaces
    $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
    // -- Returns the slug
    return strtolower(strtr($stripped, $table));
}

?>