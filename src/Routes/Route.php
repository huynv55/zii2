<?php
namespace App\Routes;

abstract class Route implements RouteInterface
{

    public static function getCurrentUrl()
    {
        return self::base().$_SERVER['REQUEST_URI'];
    }

    public static function getUrlBack() {
        return !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : self::base();
    }

    public static function getProtocol() {
        if ( !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return 'https';
        }
        if ( !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https' ) {
            return 'https';
        }
        return 'http';
    }

    public static function base() {
        $host = $_SERVER['SERVER_NAME'];
        $base_url = self::getProtocol() . "://{$host}";
        return $base_url;
    }

    public static function hostname() {
        return $_SERVER['SERVER_NAME'];
    }
}
?>