<?php
use App\Application;

$builder = new \DI\ContainerBuilder();
//$builder->useAutowiring(false);
//$builder->useAnnotations(false);
//$builder->enableCompilation(__DIR__ . '/../tmp');
$dependencies = require __DIR__."/../config/dependencies.php";
$builder->addDefinitions($dependencies);
$container = $builder->build();

return $container->get(Application::class);
?>