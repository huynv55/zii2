<?php
namespace App\Views;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use App\Routes\AppRoute;

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

    public function format($var, $format)
    {
        return date($format, strtotime($var));
    }

    public function asset($path, $enableTimetamp = false)
    {
        $url = AppRoute::base()."/".ltrim($path, "/");
        if ($enableTimetamp) {
            $f_path =  ROOT_PATH."/public/".ltrim($path, "/");
            if (file_exists($f_path))
            {
                $v = filemtime($f_path);
                return $url."?".$v;
            }
        }
        return $url;
    }

    public function base_url()
    {
        return AppRoute::base();
    }

    public function url($uri)
    {
        return AppRoute::base()."/".ltrim($uri, "/");
    }

    public function str_limit($str, $limit = 100)
    {
        $s = trim($str);
        $len = strlen($s);
        if ($len < $limit)
        {
            return $s;
        }
        return substr($s, 0 , $limit).'...';
    }
}
?>