<?php
namespace App\Views;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class AppHelper implements ExtensionInterface
{
    protected Engine $engine;

    public function register(Engine $engine)
    {
        $engine->registerFunction('app', [$this, 'getObject']);
    }

    public function getObject()
    {
        return $this;
    }

    public function now()
    {
        return date('Y-m-d H:i:s');
    }

    public function format($var, $format)
    {
        return date($format, strtotime($var));
    }
}
?>