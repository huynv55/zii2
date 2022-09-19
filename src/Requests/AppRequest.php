<?php
namespace App\Requests;

abstract class AppRequest implements RequestInterface
{
    protected array $headers;
    protected string $method;
    protected array $post;
    protected array $get;
    protected array $files;

    public function __construct()
    {
        $this->headers = apache_request_headers();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->post = $_POST;
        $this->get = $_GET;
        $this->files = $_FILES;
    }

    public function getProtocolVersion()
    {
        return $_SERVER['SERVER_PROTOCOL'];
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getHeaders() : array
    {
        return $this->headers;
    }

    public function hasHeader($name) : bool
    {
        return in_array($name, $this->headers);
    }

    public function getHeader($name) : array
    {
        $results = [];
        foreach ($this->headers as $key => $value) {
            if(strpos(strtolower(trim($key)), strtolower(trim($name))) !== false) {
                $results[strtolower(trim($key))] = $value;
            }
        }
        return $results;
    }

    public function getHeaderLine($name) : string
    {
        $results = [];
        foreach ($this->headers as $key => $value) {
            if(strpos(strtolower(trim($key)), strtolower(trim($name))) !== false) {
                $results[] = strtolower(trim($key)).': '.$value;
            }
        }
        return !empty($results) ? implode("\n", $results) : '';
    }

    public function getQueryString()
    {
        return $this->get;
    }

    public function getPostData()
    {
        return $this->post;
    }

    public function getFileUpload()
    {
        return $this->files;
    }

    public function getUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    public function validated() : bool
    {
        return true;
    }

    public function authorize() : bool
    {
        return true;
    }

    public function user()
    {
        return [];
    }
} 
?>