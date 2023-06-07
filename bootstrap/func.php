<?php

use App\Responses\HtmlExtensions\FormHelper;
use App\Responses\HtmlExtensions\PaginateHelper;
use App\Services\SessionService;
use App\Services\FlashSessionService;
use League\Plates\Engine as PhpRenderer;
use League\Plates\Extension\URI as URIHepler;
use League\Plates\Extension\Asset as AssetHepler;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Monolog\Formatter\LineFormatter;
/**
 * get instance class Monolog\Logger
 *
 * @return Logger
 */
function logger() : Logger
{
    if(
        !empty($GLOBALS[Logger::class])
        and
        $GLOBALS[Logger::class] instanceof Logger
    ) {
        return $GLOBALS[Logger::class];
    }
    $loggerSettings = [
        'name' => 'zii2-app',
        'display_error_details' => false,
        'log_errors' => true,
        'path' => __DIR__ . '/../tmp/logs/app.log',
        'level' => Level::Debug
    ];
    $logger = new Logger($loggerSettings['name']);

    $processor = new UidProcessor();
    $logger->pushProcessor($processor);
    $formatter = new LineFormatter(null,null,true);
    $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
    $handler->setFormatter($formatter);
    $logger->pushHandler($handler);
    $GLOBALS[Logger::class] = $logger;
    return $GLOBALS[Logger::class];
}

/**
 * get instance application session
 *
 * @return SessionService
 */
function session(): SessionService
{
    return ApplicationLoader::service(SessionService::class);
}


/**
 * get instance application flash session
 *
 * @return FlashSessionService
 */
function flash(): FlashSessionService
{
    return ApplicationLoader::service(FlashSessionService::class);
}

/**
 * determine if the current invocation is from CLI
 *
 * @return boolean
 */
function is_cli() : bool
{
    return ( 
        defined('STDIN') 
        or
        php_sapi_name() === 'cli'
        or
        array_key_exists('SHELL', $_ENV)
        or
        (empty($_SERVER['REMOTE_ADDR']) and !isset($_SERVER['HTTP_USER_AGENT']) and count($_SERVER['argv']) > 0)
        or
        !array_key_exists('REQUEST_METHOD', $_SERVER)
    );
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
    }
    $template = new PhpRenderer(TEMPLATE_PATH);
    $template->loadExtension(new URIHepler($_SERVER['REQUEST_URI'] ?? '/'));
    $template->loadExtension(new AssetHepler(PUBLIC_PATH));
    $template->loadExtension(new FormHelper());
    $template->loadExtension(new PaginateHelper());
    $GLOBALS[PhpRenderer::class] = $template;
    return $GLOBALS[PhpRenderer::class];
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
    }
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

/**
 * get instance \Scrawler\Arca\Database connection
 * @return \Scrawler\Arca\Database
 */
function connectionArcaParams() : \Scrawler\Arca\Database
{
    if(
        !empty($GLOBALS[\Scrawler\Arca\Database::class])
        and
        $GLOBALS[\Scrawler\Arca\Database::class] instanceof \Scrawler\Arca\Database
    ) {
        return $GLOBALS[\Scrawler\Arca\Database::class];
    }
    $connectionParams = array(
        'dbname' => env('MYSQL_DB'),
        'user' => env('MYSQL_USER', 'root'),
        'password' => env('MYSQL_PASSWORD', ''),
        'port' => env('MYSQL_PORT', 3306),
        'host' => env('MYSQL_HOST', 'localhost'),
        'driver' => 'pdo_mysql', //You can use other supported driver this is the most basic mysql driver
    );
    $db = \Scrawler\Arca\Facade\Database::connect($connectionParams);
    $GLOBALS[\Scrawler\Arca\Database::class] = $db;
    return $GLOBALS[\Scrawler\Arca\Database::class];
}

/**
 * generate csrf_token
 *
 * @return string
 */
function csrf_token(): string
{
    if (session()->has('csrf_token')) {
		return session()->get('csrf_token');
	}
	$token = bin2hex(random_bytes(35));
	session()->set('csrf_token', $token);
	return $token;
}

/**
 * input_csrf_token render input csrf token
 *
 * @return string
 */
function input_csrf_token() : string
{
    return '<input type="hidden" name="csrf_token" value="'.csrf_token().'" />';
}

/**
 * verified csrf token
 *
 * @return boolean
 */
function csrf_token_verified(string $token): bool
{
	$csrf = session()->remove('csrf_token');
	return ($token == $csrf);
}
?>