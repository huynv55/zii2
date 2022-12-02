<?php declare(strict_types=1);
$GLOBALS['composer'] = require __DIR__."/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('CONTAINER_NAME', 'container');
define('APPLICATION_NAME', 'app');
define('ROOT_PATH', __DIR__);

require __DIR__."/bootstrap/func.php";

$app = require __DIR__."/bootstrap/app.php";
$params = json_decode(json_encode($argv), true);
array_shift($params);
$classes = \ClassFinder::getInstance()->getListClassInDir(ROOT_PATH.'/src/Consoles');
$command = $argv[1] ?? '';
array_shift($params);
foreach ($classes as $key => $class) {
    if (strpos($class, 'Abstract') !== false || strpos($class, 'Interface') !== false) {
        continue;
    }
    $console = container()->get('\\App\\Consoles\\'.$class);
    if ($console->getCommand() === $command) {
        $console->setOptions($params);
        $console->run();
        die();
    }
}
?>