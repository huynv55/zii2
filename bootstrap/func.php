<?php
use League\Plates\Engine as PhpRenderer;
use League\Plates\Extension\URI as URIHepler;
use League\Plates\Extension\Asset as AssetHepler;

/**
 * get environment variable value
 *
 * @param string $env
 * @param mixed $default
 * @return mixed
 */
function env(string $env, mixed $default = null) : mixed
{
    if( getenv($env) !== FALSE) {
        return getenv($env);
    }
    if(isset($_ENV[$env])) {
        return $_ENV[$env];
    }
    if(isset($_SERVER[$env])) {
        return $_SERVER[$env];
    }
    return $default;
}

/**
 * get instance class League\Plates\Engine
 * @return PhpRenderer
 */
function phpRender(): PhpRenderer
{
    if(
        !empty($GLOBALS[PhpRenderer::class])
        and
        $GLOBALS[PhpRenderer::class] instanceof PhpRenderer
    ) {
        return $GLOBALS[PhpRenderer::class];
    } else {
        $template = new PhpRenderer(TEMPLATE_PATH);
        $template->loadExtension(new URIHepler($_SERVER['REQUEST_URI']));
        $template->loadExtension(new AssetHepler(PUBLIC_PATH));
        $GLOBALS[PhpRenderer::class] = $template;
        return $GLOBALS[PhpRenderer::class];
    }
}

/**
 * get instance db connection
 * @return \PDO
 */
function db(): \PDO
{
    if(
        !empty($GLOBALS[\PDO::class])
        and
        $GLOBALS[\PDO::class] instanceof \PDO
    ) {
        return $GLOBALS[\PDO::class];
    } else {
        $mysql = [
            'host' => env('MYSQL_HOST', 'localhost'),
            'user' => env('MYSQL_USER', 'root'),
            'password' => env('MYSQL_PASSWORD', ''),
            'port' => env('MYSQL_PORT', 3306),
            'db' => env('MYSQL_DB'),
            'charset' => env('MYSQL_CHARSET', 'utf8mb4')
        ];
        $dsn = "mysql:host=". $mysql['host'] .";port=". $mysql['port'].";dbname=".$mysql['db'].";charset=".$mysql['charset'];
        $opts = [];
        $opts[\PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES 'utf8mb4';";
        $opts[\PDO::ATTR_PERSISTENT] = true;
        $GLOBALS[\PDO::class] = new \PDO($dsn, $mysql['user'], $mysql['password'], $opts);
        $GLOBALS[\PDO::class]->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $GLOBALS[\PDO::class];
    }
}
?>