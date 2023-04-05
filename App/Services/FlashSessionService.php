<?php
namespace App\Services;

class FlashSessionService extends SessionService
{
    const SESSION_NAME = 'flash';

    public function __construct()
    {
        parent::__construct();
    }

    public function get(string $name, mixed $default = null)
    {
        $value = parent::get($name, $default);
        parent::remove($name);
        return $value;
    }
}
?>