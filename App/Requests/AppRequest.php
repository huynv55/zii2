<?php
namespace App\Requests;

class AppRequest extends AbstractRequest
{
    public function __construct()
    {
        
    }
    
	public function initialize()
	{
		parent::initialize();
	}

    public function getCookie(string $name = null, mixed $default = null)
    {
        if(empty($name)) {
            return $_COOKIE;
        }
        return $_COOKIE[$name] ?? $default;
    }

	public function getPostData(string $name = null, mixed $default = null)
    {
        if(empty($name)) {
            return $_POST;
        }
        return $_POST[$name] ?? $default;
    }

    public function getQueryString(string $name = null, mixed $default = null)
    {
        if(empty($name)) {
            return $_GET;
        }
        return $_GET[$name] ?? $default;
    }

    public function getUploadFile(string $file = null)
    {
        if(empty($file)) {
            return $_FILES;
        }
        return $_FILES[$file] ?? null;
    }
}
?>